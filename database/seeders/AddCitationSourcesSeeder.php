<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LocalheroPortal\Models\LLI\CitationSource;

class AddCitationSourcesSeeder extends Seeder
{
    public function run()
    {
        $citations = [
            ['name' => 'Facebook', 'category' => 'Allgemein', 'url' => 'https://www.facebook.com', 'score' => 1516],
            ['name' => 'Google My Business', 'category' => 'Kartendienst', 'url' => 'https://www.business.google.com', 'score' => 641],
            ['name' => 'Tripadvisor', 'category' => 'Gastro & Hotel', 'url' => 'https://www.tripadvisor.de', 'score' => 274],
            ['name' => 'Apple Maps', 'category' => 'Kartendienst', 'url' => 'https://www.mapsconnect.apple.com', 'score' => 228],
            ['name' => 'Das Telefonbuch', 'category' => 'Allgemein', 'url' => 'https://www.dastelefonbuch.de', 'score' => 137],
            ['name' => 'Meine Stadt', 'category' => 'Allgemein', 'url' => 'https://www.branchenbuch.meinestadt.de', 'score' => 129],
            ['name' => 'Das Örtliche', 'category' => 'Allgemein', 'url' => 'https://www.dasoertliche.de', 'score' => 114],
            ['name' => 'München', 'category' => 'Regional', 'url' => 'https://www.muenchen.de', 'score' => 43],
            ['name' => 'Online Street', 'category' => 'Allgemein', 'url' => 'https://www.onlinestreet.de', 'score' => 42],
            ['name' => 't3n', 'category' => 'Agenturen', 'url' => 'https://www.t3n.de', 'score' => 38],
            ['name' => 'Gelbe Seiten', 'category' => 'Allgemein', 'url' => 'https://www.gelbeseiten.de', 'score' => 38],
            ['name' => 'Cylex', 'category' => 'Allgemein', 'url' => 'https://www.web2.cylex.de', 'score' => 37],
            ['name' => 'Wer kennt den Besten', 'category' => 'Allgemein', 'url' => 'https://www.werkenntdenbesten.de', 'score' => 36],
            ['name' => 'Öffnungszeiten Buch', 'category' => 'Allgemein', 'url' => 'https://www.oeffnungszeitenbuch.de', 'score' => 36],
            ['name' => 'Jameda', 'category' => 'Ärzte', 'url' => 'https://www.jameda.de', 'score' => 35],
            ['name' => 'Wer liefert Was', 'category' => 'B2B', 'url' => 'https://www.wlw.de', 'score' => 32],
            ['name' => '11880', 'category' => 'Allgemein', 'url' => 'https://www.11880.com', 'score' => 20],
            ['name' => 'Anwalt', 'category' => 'Rechtsanwälte', 'url' => 'https://www.anwalt.de', 'score' => 19],
            ['name' => 'Go Local', 'category' => 'Allgemein', 'url' => 'https://www.golocal.de', 'score' => 12],
            ['name' => 'Jura Forum', 'category' => 'Rechtsanwälte', 'url' => 'https://www.juraforum.de', 'score' => 10],
            ['name' => 'My Hammer', 'category' => 'Handwerker', 'url' => 'https://www.my-hammer.de', 'score' => 8],
            ['name' => 'Online Marketing', 'category' => 'Agenturen', 'url' => 'https://www.onlinemarketing.de', 'score' => 6],
            ['name' => 'Arzt Auskunft', 'category' => 'Ärzte', 'url' => 'https://www.arzt-auskunft.de', 'score' => 5],
            ['name' => 'Noch Offen', 'category' => 'Allgemein', 'url' => 'https://www.nochoffen.de', 'score' => 5],
            ['name' => 'Go Yellow', 'category' => 'Allgemein', 'url' => 'https://www.goyellow.de', 'score' => 4],
            ['name' => 'Kompass', 'category' => 'Allgemein', 'url' => 'https://de.kompass.com', 'score' => 4],
            ['name' => 'Ortsdienst', 'category' => 'Allgemein', 'url' => 'https://www.ortsdienst.de', 'score' => 4],
            ['name' => 'Foursquare', 'category' => 'Gastro & Hotel', 'url' => 'https://de.foursquare.com', 'score' => 3],
            ['name' => 'Tellows', 'category' => 'Allgemein', 'url' => 'https://www.tellows.de', 'score' => 3],
            ['name' => 'Firmen ABC', 'category' => 'Regional', 'url' => 'https://www.firmenabc.at', 'score' => 3],
            ['name' => 'Finde Offen', 'category' => 'Allgemein', 'url' => 'https://www.finde-offen.de', 'score' => 3],
            ['name' => 'Open Street Map', 'category' => 'Kartendienst', 'url' => 'https://www.openstreetmap.org', 'score' => 3],
            ['name' => 'Stadtbranchenbuch', 'category' => 'Allgemein', 'url' => 'https://www.stadtbranchenbuch.com', 'score' => 3],
            ['name' => 'Firmen DB', 'category' => 'B2B', 'url' => 'https://www.firmendb.de', 'score' => 2],
            ['name' => 'Stadt Branche', 'category' => 'Allgemein', 'url' => 'https://www.stadtbranche.de', 'score' => 2],
            ['name' => 'Bing Map', 'category' => 'Kartendienst', 'url' => 'https://www.bing.com/maps', 'score' => 2],
            ['name' => 'Fachanwalt', 'category' => 'Rechtsanwälte', 'url' => 'https://www.fachanwalt.de', 'score' => 2],
            ['name' => 'Rechtsanwalt', 'category' => 'Rechtsanwälte', 'url' => 'https://www.rechtsanwalt.com', 'score' => 2],
            ['name' => 'Yelp', 'category' => 'Allgemein', 'url' => 'https://www.biz.yelp.de', 'score' => 2],
            ['name' => 'Anwalt24', 'category' => 'Rechtsanwälte', 'url' => 'https://www.anwalt24.de', 'score' => 1],
            ['name' => 'Anwaltsregister', 'category' => 'Rechtsanwälte', 'url' => 'https://www.anwaltsregister.de', 'score' => 0],
            ['name' => 'Agenturmatching', 'category' => 'Agenturen', 'url' => 'https://www.agenturmatching.de', 'score' => 0],
        ];
        foreach ($citations as $citation) {
            CitationSource::create($citation);
        }
    }
}
