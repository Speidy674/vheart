@use(App\Enums\Broadcaster\BroadcasterConsent)
<x-layout :title="'Onboarding'" class="max-w-3xl w-full mx-auto font-sans">
    <form action="#" method="POST">
        <x-ui.card class="shadow-sm border border-border bg-card text-card-foreground">
            <x-ui.card.header class="pb-6 border-b border-border">
                <x-ui.card.title class="text-center text-2xl font-bold tracking-tight">
                    <h1>Willkommen, {{ auth()->user()->name }}!</h1>
                </x-ui.card.title>
            </x-ui.card.header>

            <x-ui.card.content class="p-4 pt-6 space-y-8">
                <div class="text-center space-y-2">
                    <p class="text-lg font-medium text-foreground">
                        Lass uns dein Profil einrichten.
                    </p>
                </div>

                <section id="consent" class="space-y-5">
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-foreground">
                            Nutzungsrechte für deine Inhalte
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Damit wir deine Clips in unseren Compilations verwenden können, brauchen wir deine Erlaubnis. Du kannst das natürlich überspringen und dir erstmal alles in Ruhe anschauen.
                        </p>
                    </div>

                    <div class="grid gap-3">
                        @foreach(BroadcasterConsent::cases() as $consentOption)
                            <x-onboarding.checkbox name="consent[]" :label="$consentOption->getLabel()" :value="$consentOption->value" />
                        @endforeach
                    </div>
                </section>

                <section id="submit_permissions" class="space-y-5">
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-foreground">
                            Clip-Einsendungen
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Wer darf Clips von dir bei uns einreichen? Wir empfehlen, dies für alle Zuschauer freizugeben.
                        </p>
                    </div>

                    <div class="grid gap-3" x-data="{ all: true, vips: true, mods: true }">
                        <x-onboarding.checkbox
                            x-model="all"
                            @change="if(all) { vips = true; mods = true }"
                            checked name="everyone"
                            label="Jeder"
                            description="Jeder kann deine Clips auf unserer Seite einsenden"
                            value="1"
                        />

                        <x-onboarding.checkbox
                            x-model="vips"
                            x-bind:data-all="all"
                            @change="if(!vips) all = false"
                            checked
                            name="vips"
                            label="VIPs"
                            description="Zuschauer mit dem VIP Status können Clips einsenden"
                            value="1"
                        />

                        <x-onboarding.checkbox
                            x-model="mods"
                            x-bind:data-all="all"
                            @change="if(!mods) all = false"
                            checked
                            name="moderators"
                            label="Moderatoren"
                            description="Moderatoren können Clips einsenden"
                            value="1"
                        />
                    </div>
                </section>
            </x-ui.card.content>

            <x-ui.card.footer class="p-4 flex-col-reverse sm:flex-row justify-between md:justify-end gap-4 border-t border-border md:bottom-14 md:sticky md:z-10 bg-card text-card-foreground">
                <x-ui.button type="submit" name="action" value="skip" variant="outline">
                    Später entscheiden
                </x-ui.button>
                <x-ui.button type="submit" name="action" value="setup">
                    Speichern & Weiter
                </x-ui.button>
            </x-ui.card.footer>
        </x-ui.card>
    </form>
</x-layout>
