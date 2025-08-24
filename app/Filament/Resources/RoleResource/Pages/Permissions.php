<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Page;
use Spatie\Permission\Models\Permission;

class Permissions extends Page
{
    use InteractsWithRecord {
        configureAction as configureActionRecord;
    }
    use InteractsWithFormActions;

    public ?array $data = [];

    protected static string $resource = RoleResource::class;

    protected static string $view = 'filament.resources.role-resource.pages.permissions';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record)->load('permissions');

        $this->fillData();

        static::authorizeResourceAccess();
    }

    public function fillData()
    {
        $this->form->fill([
            'permissions' => $this->record->permissions->pluck('id')->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        $permissions = Permission::get()->mapWithKeys(fn($permission) => [$permission->id => $permission->name])->toArray();

        $lists = [];

        $lists[] = CheckboxList::make('permissions')
            ->columns(4)
            ->label('Permisos')
            ->options($permissions)
            ->nullable()
            ->bulkToggleable();

        return $form->schema($lists)->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('syncPermissions')->submit('syncPermissions')->label('Guardar Permisos')
        ];
    }

    public function syncPermissions()
    {
        $data = $this->form->getState();

        $this->record->syncPermissions(Permission::whereIn('id', $data['permissions'])->get()->pluck('name')->toArray());

        $this->fillData();

        return Notification::make()
            ->success()
            ->title('Permisos modificados')->send();
    }

    protected function getViewData(): array
    {
        return [
            'permissions' => Permission::get()->groupBy('module')->all()
        ];
    }
}
