<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    protected $user;
    protected $defaultGroupId;
    protected $upload;

    public function __construct(User $user, $defaultGroupId = null, $upload = null)
    {
        $this->user = $user;
        $this->defaultGroupId = $defaultGroupId;
        $this->upload = $upload;
    }

    public function model(array $row)
    {
        // Normalize keys: name and phone expected
        $name = $row['name'] ?? ($row['full_name'] ?? null);
        $phone = $row['phone'] ?? ($row['mobile'] ?? null);
        $groupName = $row['group'] ?? ($row['contact_group'] ?? null);

        if (!$phone) return null;

        // If a default group was provided, prefer it. Otherwise, create/find by name from file.
        $groupId = $this->defaultGroupId;
        if (!$groupId && $groupName) {
            $group = ContactGroup::firstOrCreate(
                ['user_id' => $this->user->id, 'name' => Str::limit($groupName, 120)],
                ['name' => $groupName]
            );
            $groupId = $group->id;
        }

        $data = [
            'user_id' => $this->user->id,
            'contact_group_id' => $groupId,
            'name' => $name,
            'phone' => preg_replace('/\D/', '', $phone),
            'meta' => [],
        ];

        if ($this->upload && method_exists($this->upload, 'id')) {
            $data['upload_id'] = $this->upload->id;
        }

        // If we have an upload record, increment processed rows for basic progress tracking
        if ($this->upload && method_exists($this->upload, 'incrementProcessedRows')) {
            try {
                $this->upload->incrementProcessedRows();
            } catch (\Throwable $e) {
                // ignore increment errors
            }
        }

        return new Contact($data);
    }
}
