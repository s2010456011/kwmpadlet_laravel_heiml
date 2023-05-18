<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entry = new Entrie();
        $entry->title = "Eintrag im Padlet";
        $entry->text = "Beschreibungstext";

        //erstes Padlet selektieren
        $padlet = Padlet::first();
        //eintrag zu padlet hinzufügen
        $entry->padlet()->associate($padlet);

        //ersten User speichern
        $user = User::first();
        //user zu Entry hinzufügen
        $entry->user()->associate($user);
        $entry->save();

        //kommentar erstellen
        $comment = new Comment();
        //kommentar befüllen
        $comment->text = "Toller Beitrag!";
        //kommentar einem User zuordnen
        $comment->user()->associate($user);

        $comment2 = new Comment();
        $comment2->text = "Finde ich genau so!";
        $comment2->user()->associate($user);

        //mehrere Kommentare werden zum Entry gespeichert
        $entry->comments()->saveMany([$comment, $comment2]);

        $entry2 = new Entrie();
        $entry2->title = "Eintrag im zweietne Padlet";
        $entry2->text = "wefwefwe Beschreibungstext";

        $padlet = Padlet::first();
        $entry2->padlet()->associate($padlet);

        $user = User::first();
        $entry2->user()->associate($user);

        $entry2->save();


    }
}
