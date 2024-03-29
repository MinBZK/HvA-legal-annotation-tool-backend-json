<?php

declare(strict_types=1);

namespace Tests\Http\GraphQL\Models\Law\Mutations;

use App\Models\Article;
use App\Models\Law;
use App\Models\Matter;
use App\Models\RelationSchema;
use Tests\Http\GraphQL\AbstractHttpGraphQLTestCase;

class SaveLawTest extends AbstractHttpGraphQLTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Matter::factory()->createMany([
            [
                'id' => $this->createUUIDFromID(1),
            ],
            [
                'id' => $this->createUUIDFromID(2),
            ],
        ]);

        Law::factory()->create([
            'id'           => $this->createUUIDFromID(1),
            'is_published' => false,
        ]);

        Article::factory()->createMany([
            [
                'id'     => $this->createUUIDFromID(1),
                'title'  => 'Article 1',
                'text'   => 'This is the first article',
                'law_id' => $this->createUUIDFromID(1),
            ],
            [
                'id'     => $this->createUUIDFromID(2),
                'title'  => 'Article 2',
                'text'   => 'This is the second article',
                'law_id' => $this->createUUIDFromID(1),
            ],
        ]);

        RelationSchema::factory()->create([
            'id' => $this->createUUIDFromID(1),
        ]);
    }

    /**
     * @test
     */
    public function it_saves_a_law_with_annotations()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
          mutation saveAnnotatedLaw($input: AnnotatedLawInput!) {
              saveAnnotatedLaw(input: $input) {
                  id
                  isPublished
                  articles {
                      id
                      title
                      text
                      revision {
                        jsonText
                        annotations {
                            text
                             definition
                             comment
                             matter {
                                 id
                             }
                        }
                      }
                  }
              }
          }
        ', [
            'input' => [
                'lawId'       => $this->createUUIDFromID(1),
                'isPublished' => false,
                'articles'    => [
                    [
                        'articleId'   => $this->createUUIDFromID(1),
                        'jsonText'    => '{"text": "this is the json text"}',
                        'annotations' => [
                            [
                                'text'       => 'This is the first text of the annotation',
                                'definition' => 'This is the first definition of the annotation',
                                'comment'    => 'This is the first comment of the annotation',
                                'matterId'   => $this->createUUIDFromID(1),
                                'tempId'     => $this->createUUIDFromID(1),
                            ],
                            [
                                'text'       => 'This is the second text of the annotation',
                                'definition' => 'This is the second definition of the annotation',
                                'comment'    => 'This is the second comment of the annotation',
                                'matterId'   => $this->createUUIDFromID(2),
                                'tempId'     => $this->createUUIDFromID(1),
                            ],
                        ],
                    ],
                    [
                        'articleId'   => $this->createUUIDFromID(2),
                        'jsonText'    => '{"text": "this is the json text"}',
                        'annotations' => [],
                    ],
                ],
            ],
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'saveAnnotatedLaw' => [
                    'id'          => $this->createUUIDFromID(1),
                    'isPublished' => false,
                    'articles'    => [
                        [
                            'id'       => $this->createUUIDFromID(1),
                            'title'    => 'Article 1',
                            'text'     => null,
                            'revision' => [
                                'jsonText'    => '{"text": "this is the json text"}',
                                'annotations' => [
                                    [
                                        'text'       => 'This is the first text of the annotation',
                                        'definition' => 'This is the first definition of the annotation',
                                        'comment'    => 'This is the first comment of the annotation',
                                        'matter'     => [
                                            'id' => $this->createUUIDFromID(1),
                                        ],
                                    ],
                                    [
                                        'text'       => 'This is the second text of the annotation',
                                        'definition' => 'This is the second definition of the annotation',
                                        'comment'    => 'This is the second comment of the annotation',
                                        'matter'     => [
                                            'id' => $this->createUUIDFromID(2),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'id'       => $this->createUUIDFromID(2),
                            'title'    => 'Article 2',
                            'text'     => 'This is the second article',
                            'revision' => null,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
