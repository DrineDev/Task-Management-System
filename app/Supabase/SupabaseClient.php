<?php

namespace App\Supabase;

use Illuminate\Support\Facades\Http;

class SupabaseClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected array $headers;

    protected string $table;
    protected string $select = '*';
    protected array $filters = [];
    protected ?string $order = null;
    protected array $updateData = [];

    public function __construct()
    {
        $this->baseUrl = config('services.supabase.url');
        $this->apiKey = config('services.supabase.key');
        $this->headers = [
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(string $columns = '*'): self
    {
        $this->select = $columns;
        return $this;
    }

    public function eq(string $column, $value): self
    {
        $this->filters[$column] = $value;
        return $this;
    }

    public function order(string $column): self
    {
        $this->order = $column;
        return $this;
    }

    public function get()
    {
        $url = $this->baseUrl.'/rest/v1/'.$this->table;

        $query = ['select' => $this->select];

        foreach ($this->filters as $column => $value) {
            $query[$column] = 'eq.'.$value;
        }

        if ($this->order) {
            $query['order'] = $this->order;
        }

        $response = Http::withHeaders($this->headers)
            ->get($url, $query);

        $this->resetQuery();

        return $response;
    }

    public function insert(array $data)
    {
        $url = $this->baseUrl.'/rest/v1/'.$this->table;

        $response = Http::withHeaders($this->headers)
            ->post($url, $data);

        $this->resetQuery();

        return $response;
    }

    public function update(array $data): self
    {
        $this->updateData = $data;
        return $this;
    }

    public function patch()
    {
        $url = $this->baseUrl.'/rest/v1/'.$this->table;

        $query = [];
        foreach ($this->filters as $column => $value) {
            $query[$column] = 'eq.'.$value;
        }

        $response = Http::withHeaders($this->headers)
            ->patch($url, $this->updateData, $query);

        $this->resetQuery();

        return $response;
    }

    public function delete()
    {
        $url = $this->baseUrl.'/rest/v1/'.$this->table;

        $query = [];
        foreach ($this->filters as $column => $value) {
            $query[$column] = 'eq.'.$value;
        }

        $response = Http::withHeaders($this->headers)
            ->delete($url, $query);

        $this->resetQuery();

        return $response;
    }

    protected function resetQuery(): void
    {
        $this->select = '*';
        $this->filters = [];
        $this->order = null;
        $this->updateData = [];
    }
}
