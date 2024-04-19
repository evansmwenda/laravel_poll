<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollAnswerRequest;
use App\Http\Requests\StorePollRequest;
use App\Http\Requests\UpdatePollRequest;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollQuestion;
use App\Models\PollQuestionAnswer;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // return PollResource::collection(Poll::where('user_id',$user->id)->orderBy('created_at', 'DESC')->paginate('10'));
        return PollResource::collection(Poll::where('status',1)->orderBy('created_at', 'DESC')->paginate('10'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request)
    {
        $data = $request->validated();

        if(isset($data['image'])){
            //save the image
            $imagePath = $this->saveImage($data['image']);
            $data['image'] = $imagePath;
        }

        $poll = Poll::create($data);

        //create questions
        foreach ($data['questions'] as $question) {
            $question['poll_id'] = $poll->id;
            $this->createQuestion($question);
        }


        return new PollResource($poll);
    }

    /**
     * Display the specified resource.
     */
    public function show(Poll $poll,Request $request)
    {
        $user = $request->user();
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        return new PollResource($poll);
    }

    public function showForGuest(Poll $poll,Request $request)
    {
        if (!$poll->status) {
            return response("", 404);
        }

        $currentDate = new \DateTime();
        $expireDate = new \DateTime($poll->expiry_date);
        if ($currentDate > $expireDate) {
            return response("", 404);
        }

        return new PollResource($poll);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollRequest $request, Poll $poll)
    {
        $data = $request->validated();

        if(isset($data['image'])){
            //save the image
            $imagePath = $this->saveImage($data['image']);
            $data['image'] = $imagePath;

            //delete old image
            if($poll->image){
                $absolutePath = public_path($poll->image);
                File::delete($absolutePath);
            }
        }

        $poll->update($data);

        // Get ids as plain array of existing questions
        $existingIds = $poll->questions()->pluck('id')->toArray();
        // Get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'], 'id');
        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);
        //Find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        // Delete questions by $toDelete array
        PollQuestion::destroy($toDelete);

        // Create new questions
        foreach ($data['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['poll_id'] = $poll->id;
                $this->createQuestion($question);
            }
        }

        // Update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach ($poll->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }
        return new PollResource($poll);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll,Request $request)
    {
        $user = $request->user(); 
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        //delete old image
        if($poll->image){
            $absolutePath = public_path($poll->image);
            File::delete($absolutePath);
        }

        $poll->delete();
        return response('',204);
    }


    public function storeAnswer(StorePollAnswerRequest $request, Poll $poll)
    {
        $validated = $request->validated();
//        var_dump($validated, $survey);

        $surveyAnswer = PollAnswer::create([
            'poll_id' => $poll->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = PollQuestion::where(['id' => $questionId, 'poll_id' => $poll->id])->get();
            if (!$question) {
                return response("Invalid question ID: \"$questionId\"", 400);
            }

            $data = [
                'poll_question_id' => $questionId,
                'poll_answer_id' => $surveyAnswer->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer
            ];

            $questionAnswer = PollQuestionAnswer::create($data);
        }

        return response("", 201);

    }

    private function createQuestion($data){
        if(is_array($data['data'])){
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data,[
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Poll::TYPE_TEXT,
                Poll::TYPE_TEXTAREA,
                Poll::TYPE_SELECT,
                Poll::TYPE_RADIO,
                Poll::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'poll_id' => 'exists:App\Models\Poll,id'
        ]);

        return PollQuestion::create($validator->validated());

    }

    private function updateQuestion(PollQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\PollQuestion,id',
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Poll::TYPE_TEXT,
                Poll::TYPE_TEXTAREA,
                Poll::TYPE_SELECT,
                Poll::TYPE_RADIO,
                Poll::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
        ]);

        return $question->update($validator->validated());
    }

    public function saveImage($image){
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }

    public function createQuestionAnswer($data)
    {
        if (is_array($data['answer'])) {
            $data['answer'] = json_encode($data['answer']);
        }
    }

    
}
