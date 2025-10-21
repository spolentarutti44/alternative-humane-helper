<?php

namespace Database\Seeders;

use App\Models\FurryFriend;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Foster;
use App\Models\FosterAssignment;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'username' => 'admin',
            'email' => 'admin@nonprofit.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create volunteer user
        $volunteerUser = User::create([
            'username' => 'volunteer',
            'email' => 'volunteer@nonprofit.com',
            'password' => Hash::make('password123'),
            'role' => 'volunteer',
        ]);

        // Create volunteer profile
        Volunteer::create([
            'user_id' => $volunteerUser->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '555-0102',
            'availability' => 'Weekends',
            'skills' => 'Animal care, Photography',
            'background_check_date' => now()->subMonths(3),
            'status' => 'active',
        ]);

        // Create sample furry friends
        $furryFriends = [
            [
                'name' => 'Max',
                'species' => 'dog',
                'breed' => 'Labrador Retriever',
                'age' => 3,
                'gender' => 'male',
                'status' => 'available',
                'description' => 'Friendly and energetic dog, great with kids.',
                'intake_date' => now()->subDays(30),
            ],
            [
                'name' => 'Luna',
                'species' => 'cat',
                'breed' => 'Siamese',
                'age' => 2,
                'gender' => 'female',
                'status' => 'available',
                'description' => 'Sweet and calm cat, loves to cuddle.',
                'intake_date' => now()->subDays(15),
            ],
            [
                'name' => 'Charlie',
                'species' => 'dog',
                'breed' => 'Beagle',
                'age' => 5,
                'gender' => 'male',
                'status' => 'fostered',
                'description' => 'Playful and curious, needs regular exercise.',
                'intake_date' => now()->subDays(45),
            ],
            [
                'name' => 'Bella',
                'species' => 'cat',
                'breed' => 'Persian',
                'age' => 1,
                'gender' => 'female',
                'status' => 'available',
                'description' => 'Young and playful, loves toys.',
                'intake_date' => now()->subDays(7),
            ],
        ];

        foreach ($furryFriends as $furryFriendData) {
            FurryFriend::create($furryFriendData);
        }

        // Create foster families
        $fosters = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '555-0201',
                'address' => '123 Main St, City, State 12345',
                'capacity' => 2,
                'status' => 'active',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.j@example.com',
                'phone' => '555-0202',
                'address' => '456 Oak Ave, City, State 12345',
                'capacity' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($fosters as $fosterData) {
            Foster::create($fosterData);
        }

        // Create foster assignment
        FosterAssignment::create([
            'foster_id' => 1,
            'furry_friend_id' => 3, // Charlie
            'start_date' => now()->subDays(10),
            'status' => 'active',
            'notes' => 'Doing well, adapting nicely.',
        ]);

        // Create schedules
        Schedule::create([
            'title' => 'Vet Checkup - Max',
            'description' => 'Annual checkup and vaccinations',
            'type' => 'medical',
            'furry_friend_id' => 1,
            'start_time' => now()->addDays(3)->setTime(10, 0),
            'end_time' => now()->addDays(3)->setTime(11, 0),
            'location' => 'City Veterinary Clinic',
            'status' => 'scheduled',
            'created_by' => $adminUser->id,
        ]);

        Schedule::create([
            'title' => 'Grooming Appointment - Luna',
            'description' => 'Regular grooming session',
            'type' => 'grooming',
            'furry_friend_id' => 2,
            'start_time' => now()->addDays(5)->setTime(14, 0),
            'end_time' => now()->addDays(5)->setTime(15, 0),
            'location' => 'Pet Grooming Center',
            'status' => 'scheduled',
            'created_by' => $adminUser->id,
        ]);

        // Create donors
        $donors = [
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.b@example.com',
                'phone' => '555-0301',
                'address' => '789 Pine Rd, City, State 12345',
                'is_recurring' => true,
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.d@example.com',
                'phone' => '555-0302',
                'is_recurring' => false,
            ],
        ];

        foreach ($donors as $donorData) {
            Donor::create($donorData);
        }

        // Create donations
        $donations = [
            [
                'donor_id' => 1,
                'amount' => 100.00,
                'donation_type' => 'monetary',
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-' . uniqid(),
                'status' => 'completed',
                'donation_date' => now()->subDays(20),
                'tax_receipt_sent' => true,
            ],
            [
                'donor_id' => 2,
                'amount' => 250.00,
                'donation_type' => 'monetary',
                'payment_method' => 'paypal',
                'transaction_id' => 'TXN-' . uniqid(),
                'status' => 'completed',
                'donation_date' => now()->subDays(5),
                'tax_receipt_sent' => false,
            ],
            [
                'donor_id' => 1,
                'amount' => 100.00,
                'donation_type' => 'monetary',
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-' . uniqid(),
                'status' => 'completed',
                'donation_date' => now()->subMonths(1),
                'tax_receipt_sent' => true,
            ],
        ];

        foreach ($donations as $donationData) {
            Donation::create($donationData);
        }

        // Create events
        $events = [
            [
                'title' => 'Adoption Fair',
                'description' => 'Come meet our adorable pets looking for forever homes!',
                'event_type' => 'adoption_event',
                'start_date' => now()->addDays(14)->setTime(10, 0),
                'end_date' => now()->addDays(14)->setTime(16, 0),
                'location' => 'Community Center',
                'capacity' => 50,
                'registration_required' => false,
                'status' => 'published',
            ],
            [
                'title' => 'Volunteer Training Session',
                'description' => 'Learn about animal care and shelter operations',
                'event_type' => 'volunteer_training',
                'start_date' => now()->addDays(7)->setTime(18, 0),
                'end_date' => now()->addDays(7)->setTime(20, 0),
                'location' => 'Shelter Main Building',
                'capacity' => 20,
                'registration_required' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Annual Fundraiser Gala',
                'description' => 'Join us for an evening of fun and fundraising!',
                'event_type' => 'fundraiser',
                'start_date' => now()->addDays(30)->setTime(19, 0),
                'end_date' => now()->addDays(30)->setTime(23, 0),
                'location' => 'Grand Hotel Ballroom',
                'capacity' => 100,
                'registration_required' => true,
                'status' => 'published',
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            // Create some registrations for events that require it
            if ($event->registration_required) {
                EventRegistration::create([
                    'event_id' => $event->id,
                    'attendee_name' => 'Robert Wilson',
                    'attendee_email' => 'robert.w@example.com',
                    'attendee_phone' => '555-0401',
                    'attendance_status' => 'registered',
                ]);
            }
        }

        echo "Database seeded successfully!\n";
        echo "Admin login: admin@nonprofit.com / password123\n";
        echo "Volunteer login: volunteer@nonprofit.com / password123\n";
    }
}

