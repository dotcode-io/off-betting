<div>
    <flux:card class="space-y-6">
        <div class="flex">
            <div class="flex-1">
                <flux:heading size="lg">Fight #</flux:heading>

                <flux:subheading>
                    <p>Your post will be deleted permanently.</p>
                    <p>This action cannot be undone.</p>
                </flux:subheading>
            </div>

            <div class="-mx-2 -mt-2">
                <flux:button variant="ghost" size="sm" icon="x-mark" inset="top right bottom"/>
            </div>
        </div>

        <div class="flex gap-4">
            <flux:spacer/>
            <flux:button variant="ghost">Undo</flux:button>
            <flux:button variant="danger">Delete</flux:button>
        </div>
    </flux:card>
</div>
