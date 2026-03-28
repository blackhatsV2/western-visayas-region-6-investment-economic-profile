<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectContent;
use App\Services\AIChatService;
use Illuminate\Support\Arr;

class SyncAIContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:sync-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync region 6 profile content to Pinecone for AI RAG';

    /**
     * Execute the console command.
     */
    public function handle(AIChatService $chatService)
    {
        $this->info('Starting content sync to Pinecone...');

        $contents = ProjectContent::all();
        $vectors = [];

        foreach ($contents as $item) {
            // Check if content is array (due to JSON cast) or something else
            $parsedContent = is_array($item->content) ? implode(" ", Arr::flatten($item->content)) : (string)$item->content;
            
            $textChunk = "Section: {$item->section_title}. Page: {$item->page_number}. Content: {$parsedContent}";
            
            $this->info("Embedding block ID: {$item->id}...");
            
            $embedding = $chatService->getEmbedding($textChunk);
            
            if (!empty($embedding)) {
                $vectors[] = [
                    'id' => 'project_content_' . $item->id,
                    'values' => $embedding,
                    'metadata' => [
                        'source' => 'region6_profile',
                        'section_title' => $item->section_title ?? '',
                        'type' => $item->type ?? '',
                        'text' => $textChunk
                    ]
                ];
            } else {
                $this->error("Failed to generate embedding for block ID: {$item->id}");
            }
        }

        if (!empty($vectors)) {
            $this->info("Upserting " . count($vectors) . " vectors to Pinecone...");
            // Bulk upsert in reasonable sizes
            $chunks = array_chunk($vectors, 50);
            $successCount = 0;
            foreach ($chunks as $chunk) {
                if ($chatService->upsertToPinecone($chunk)) {
                    $successCount++;
                } else {
                    $this->error('Failed to upsert a chunk.');
                }
            }
            $this->info("Sync complete! Inserted {$successCount} chunks of vectors.");
        } else {
            $this->warn('No vectors generated.');
        }
    }
}
