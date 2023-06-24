<?php


use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_behaves_as_expected_when_listing_reports(): void
    {
        $reports = Report::factory(3)->create();

        $response = $this->get(route('reports.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'external_id',
                    'title',
                    'url',
                    'summary',
                ],
                [
                    'external_id',
                    'title',
                    'url',
                    'summary',
                ],
                [
                    'external_id',
                    'title',
                    'url',
                    'summary',
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_behaves_as_expected_when_listing_reports_with_filter(): void
    {
        $reports = Report::factory(2)->create();
        $searchReport = Report::factory()->create([
            'title' => 'NASA'
        ]);

        $response = $this->get(route('reports.index', ['filter' => 'NASA']));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'external_id',
                    'title',
                    'url',
                    'summary',
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_behaves_as_expected_when_creating_one_report(): void
    {
        $data = [
            'external_id' => 12312,
            'title' => 'This is a title',
            'url' => 'niceurl.com.br/nice',
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->post(
            route('reports.store'),
            $data
        );

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'external_id',
                'title',
                'url',
                'summary',
            ]
        ]);
        $this->assertDatabaseCount('reports', 1);
    }

    /**
     * @test
     */
    public function it_behaves_as_expected_when_deleting_the_report(): void
    {
        $report = Report::factory()->create();

        $response = $this->delete(
            route(
                'reports.destroy',
                ['report' => $report->getKey()]
            )
        );

        $response->assertNoContent();
        $this->assertDatabaseCount('reports', 0);
    }

    /**
     * @test
     */
    public function it_should_not_allow_when_creating_one_report_without_required_fields(): void
    {
        $data = [
            'title' => 'This is a title',
            'url' => 'niceurl.com.br/nice',
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->post(
            route('reports.store'),
            $data
        );

        $response->assertUnprocessable();
        $this->assertDatabaseCount('reports', 0);
    }

    /**
     * @test
     */
    public function it_should_not_allow_when_deletind_one_report_that_does_not_exist(): void
    {
        $report = Report::factory()->create();

        $response = $this->delete(
            route(
                'reports.destroy',
                ['report' => $report->getKey() + 33441]
            )
        );

        $response->assertNotFound();
        $this->assertDatabaseCount('reports', 1);
    }

    /**
     * @test
     */
    public function it_behaves_as_expected_when_creating_many_reports(): void
    {
        $response = $this->post(route('reports.store-many', ['quantity' => 10]));

        $response->assertCreated();
        $this->assertDatabaseCount('reports', 10);
    }
}
