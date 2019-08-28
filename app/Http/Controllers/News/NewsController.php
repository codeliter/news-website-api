<?php


namespace App\Http\Controllers\News;


use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Add news\
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'min:3|unique:news',
            'body' => 'min:5'
        ]);

        $data = $request->all(['title', 'body']);
        $data['created_by'] = $request->auth->id;

        if (!News::create($data))
            return $this->respondWithError('News could not be added, please try again.');

        return $this->respondWithSuccess('News added successfully.');
    }

    /**
     * Delete a news
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $news = $this->fetchNews($id);

        if (!$news->delete())
            return $this->respondWithError('News could not be deleted, please try again.', 404);

        return $this->respondWithSuccess('News deleted successfully.');
    }

    /**
     * List all news
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $perPage = $request->get('perPage') ?? 12;

        $news = News::orderBy('created_at', 'desc')->paginate($perPage);

        return $this->respondWithSuccess(['data' => $news]);
    }

    /**
     * Search Items
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $perPage = $request->get('perPage') ?? 12;
        $q = $request->get('q');

        $news = News::whereRaw('title LIKE ?', ["%" . $q . "%"])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['q' => $q]);

        return $this->respondWithSuccess(['data' => $news]);
    }

    /**
     * Fetch News Details
     * @param string $id
     * @return JsonResponse
     */
    public function details(string $id): JsonResponse
    {
        $news = $this->fetchNews($id);

        return $this->respondWithSuccess(['data' => $news]);
    }

    /**
     * Fetch News
     * @param string $id
     * @return mixed
     */
    protected function fetchNews(string $id)
    {
        $news = News::where('id', $id)->first();

        if (!$news) {
            $this->respondWithError('News does not exist', 404)->send();
            exit;
        }

        return $news;
    }
}