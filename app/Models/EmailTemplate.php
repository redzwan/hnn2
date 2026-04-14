<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['key', 'name', 'subject', 'body', 'variables', 'is_active'];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Find a template by its key.
     */
    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->where('is_active', true)->first();
    }

    /**
     * Render the subject with the given variable values.
     *
     * @param  array<string, string>  $data
     */
    public function renderSubject(array $data): string
    {
        return $this->interpolate($this->subject, $data);
    }

    /**
     * Render the body with the given variable values.
     *
     * @param  array<string, string>  $data
     */
    public function renderBody(array $data): string
    {
        return $this->interpolate($this->body, $data);
    }

    /**
     * Replace {variable} placeholders in a string.
     *
     * @param  array<string, string>  $data
     */
    private function interpolate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{'.$key.'}', (string) $value, $template);
        }

        return $template;
    }
}
