<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportCreateRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class ReportController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filter = $request->input('filter');

        $result = Report::query()
            ->when($request->has('filter'), function (Builder $query) use ($filter) {
                return $query->where('title', 'LIKE', "%$filter%")
                    ->orWhere('summary', 'LIKE', "%$filter%");
            })
            ->paginate(10);

        return ReportResource::collection($result);
    }

    /**
     * @param ReportCreateRequest $request
     * @return JsonResponse
     */
    public function store(ReportCreateRequest $request): JsonResponse
    {
        return (new ReportResource(Report::create($request->validated())))
            ->response()
            ->setStatusCode(SymfonyResponse::HTTP_CREATED);
    }

    /**
     * @param Report $report
     * @return Response|ResponseFactory
     */
    public function destroy(Report $report): Response|ResponseFactory
    {
        if (!empty($report)) {
            $report->delete();
            return response('', SymfonyResponse::HTTP_NO_CONTENT);
        }

        return response('', SymfonyResponse::HTTP_NOT_FOUND);
    }

    /**
     * @throws GuzzleException
     */
    public function storeManyReports(Request $request): JsonResponse
    {
        $filter = $request->input('filter');
        $quantity = $request->input('quantity');

        $apiUrl = 'https://api.spaceflightnewsapi.net/v4/reports/';

        $request->has('quantity') ? $apiUrl .= "?limit=$quantity" : $apiUrl .= '?limit=100';

        if ($request->has('filter')) {
            $apiUrl .= "&search=$filter";
        }

        $guzzle = new Client([
            'base_uri' => $apiUrl
        ]);

        $reports = json_decode($guzzle->get('')->getBody(), true);

        $results = [];

        foreach ($reports['results'] as $report) {
            $reportQuery = Report::query()->where('external_id', $report['id']);
            $report['external_id'] = $report['id'];

            if ($reportQuery->exists()) {
                $reportModel = $reportQuery->update($report);
            } else {
                $reportModel = Report::create($report);
            }

            $results[] = $reportModel;
        }

        return response()->json(
            ReportResource::collection($results),
            SymfonyResponse::HTTP_CREATED
        );
    }
}
