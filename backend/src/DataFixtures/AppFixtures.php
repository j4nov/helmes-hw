<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Sector;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $sectors = [
            ['id' => 1, 'label' => 'Manufacturing'],
            ['id' => 19, 'label' => 'Construction materials'],
            ['id' => 18, 'label' => 'Electronics and Optics'],
            ['id' => 6, 'label' => 'Food and Beverage'],
            ['id' => 342, 'label' => 'Bakery & confectionery products'],
            ['id' => 43, 'label' => 'Beverages'],
            ['id' => 42, 'label' => 'Fish & fish products'],
            ['id' => 40, 'label' => 'Meat & meat products'],
            ['id' => 39, 'label' => 'Milk & dairy products'],
            ['id' => 437, 'label' => 'Other'],
            ['id' => 378, 'label' => 'Sweets & snack food'],
            ['id' => 13, 'label' => 'Furniture'],
            ['id' => 389, 'label' => 'Bathroom/sauna'],
            ['id' => 385, 'label' => 'Bedroom'],
            ['id' => 390, 'label' => 'Children’s room'],
            ['id' => 98, 'label' => 'Kitchen'],
            ['id' => 101, 'label' => 'Living room'],
            ['id' => 392, 'label' => 'Office'],
            ['id' => 394, 'label' => 'Other (Furniture)'],
            ['id' => 341, 'label' => 'Outdoor'],
            ['id' => 99, 'label' => 'Project furniture'],
            ['id' => 12, 'label' => 'Machinery'],
            ['id' => 94, 'label' => 'Machinery components'],
            ['id' => 91, 'label' => 'Machinery equipment/tools'],
            ['id' => 224, 'label' => 'Manufacture of machinery'],
            ['id' => 97, 'label' => 'Maritime'],
            ['id' => 271, 'label' => 'Aluminium and steel workboats'],
            ['id' => 269, 'label' => 'Boat/Yacht building'],
            ['id' => 230, 'label' => 'Ship repair and conversion'],
            ['id' => 93, 'label' => 'Metal structures'],
            ['id' => 508, 'label' => 'Other'],
            ['id' => 227, 'label' => 'Repair and maintenance service'],
            ['id' => 11, 'label' => 'Metalworking'],
            ['id' => 67, 'label' => 'Construction of metal structures'],
            ['id' => 263, 'label' => 'Houses and buildings'],
            ['id' => 267, 'label' => 'Metal products'],
            ['id' => 542, 'label' => 'Metal works'],
            ['id' => 75, 'label' => 'CNC-machining'],
            ['id' => 62, 'label' => 'Forgings, Fasteners'],
            ['id' => 69, 'label' => 'Gas, Plasma, Laser cutting'],
            ['id' => 66, 'label' => 'MIG, TIG, Aluminum welding'],
            ['id' => 9, 'label' => 'Plastic and Rubber'],
            ['id' => 54, 'label' => 'Packaging'],
            ['id' => 556, 'label' => 'Plastic goods'],
            ['id' => 559, 'label' => 'Plastic processing technology'],
            ['id' => 55, 'label' => 'Blowing'],
            ['id' => 57, 'label' => 'Moulding'],
            ['id' => 53, 'label' => 'Plastics welding and processing'],
            ['id' => 560, 'label' => 'Plastic profiles'],
            ['id' => 5, 'label' => 'Printing'],
            ['id' => 148, 'label' => 'Advertising'],
            ['id' => 150, 'label' => 'Book/Periodicals printing'],
            ['id' => 145, 'label' => 'Labelling and packaging printing'],
            ['id' => 7, 'label' => 'Textile and Clothing'],
            ['id' => 44, 'label' => 'Clothing'],
            ['id' => 45, 'label' => 'Textile'],
            ['id' => 8, 'label' => 'Wood'],
            ['id' => 337, 'label' => 'Other (Wood)'],
            ['id' => 51, 'label' => 'Wooden building materials'],
            ['id' => 47, 'label' => 'Wooden houses'],
            ['id' => 3, 'label' => 'Other'],
            ['id' => 37, 'label' => 'Creative industries'],
            ['id' => 29, 'label' => 'Energy technology'],
            ['id' => 33, 'label' => 'Environment'],
            ['id' => 2, 'label' => 'Service'],
            ['id' => 25, 'label' => 'Business services'],
            ['id' => 35, 'label' => 'Engineering'],
            ['id' => 28, 'label' => 'Information Technology and Telecommunications'],
            ['id' => 581, 'label' => 'Data processing, Web portals, E-marketing'],
            ['id' => 576, 'label' => 'Programming, Consultancy'],
            ['id' => 121, 'label' => 'Software, Hardware'],
            ['id' => 122, 'label' => 'Telecommunications'],
            ['id' => 22, 'label' => 'Tourism'],
            ['id' => 141, 'label' => 'Translation services'],
            ['id' => 21, 'label' => 'Transport and Logistics'],
            ['id' => 111, 'label' => 'Air'],
            ['id' => 114, 'label' => 'Rail'],
            ['id' => 112, 'label' => 'Road'],
            ['id' => 113, 'label' => 'Water'],
        ];

        foreach ($sectors as $sectorData) {
            $sector = new Sector();
            $sector->setId($sectorData['id']);
            $sector->setLabel($sectorData['label']);
            $manager->persist($sector);
        }

        $manager->flush();
    }
}
