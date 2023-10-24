<x-filament-panels::page>
    <div>
        {{ $this->cacheDeleteAction }}
    </div>
    {{--<form wire:submit.prevent="submit">
        {{ $this->form }}
        <button type="submit">Save</button>
    </form>--}}

    <x-filament::button
        color="warning"
        size="xl"
        icon="heroicon-m-sparkles"
        icon-position="after"
        tooltip="My Button"
        outlined="true"
    >
        My Button
        <x-slot name="badge">77</x-slot>
    </x-filament::button>

    <x-filament::badge
        color="success"
    >
        New
    </x-filament::badge>

    <x-filament::icon-button
        icon="heroicon-m-plus"
        color="gray"
        label="New label"
    />

    <x-filament::fieldset>
        <x-slot name="label">
            Address
        </x-slot>
        Lorem ipsum dolor sit amet.
    </x-filament::fieldset>

    <x-filament::section>
        <x-slot name="heading">
            User details
        </x-slot>

        <x-slot name="description">
            This is all the information we hold about the user.
        </x-slot>

        lorem5
    </x-filament::section>

    <x-filament::section
        collapsible
        collapsed
    >
        <x-slot name="heading">
            User details
        </x-slot>

        Lorem ipsum dolor sit amet, consectetur.
    </x-filament::section>

</x-filament-panels::page>
