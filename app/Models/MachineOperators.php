<?php

namespace App\Models;

use Database\Factories\MachineOperatorsFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $position
 * @property int $team_id
 * @property int $shift_id
 * @property int $section_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'position', 'team_id', 'shift_id', 'section_id', 'created_at', 'updated_at'])]
class MachineOperators extends Model
{
    /** @use HasFactory<MachineOperatorsFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function team()
    {
        return $this->belongsTo(Teams::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shifts::class);
    }

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
