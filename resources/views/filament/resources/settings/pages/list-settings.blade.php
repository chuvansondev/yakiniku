<x-filament-panels::page>
    <style>
        .settings-save-bar {
            position: sticky;
            bottom: 1rem;
            z-index: 20;
            width: fit-content;
            margin: 1.5rem 0 0 auto;
            padding: .5rem;
            border: 1px solid rgb(229 231 235 / .85);
            border-radius: .75rem;
            background: rgb(255 255 255 / .94);
            box-shadow: 0 8px 24px rgb(0 0 0 / .12);
            backdrop-filter: blur(8px);
        }
        .dark .settings-save-bar {
            border-color: rgb(75 85 99 / .85);
            background: rgb(31 41 55 / .94);
        }
    </style>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="settings-save-bar">
            <x-filament::button type="submit" icon="heroicon-m-check">
                Lưu thay đổi
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
