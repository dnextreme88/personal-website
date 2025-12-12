<?php

namespace Database\Seeders;

use App\Models\Blog\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Journal Entries #s 1 ~ 12
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'NSTP 1 Phase 2',
            'description' => '<p>Today was the start of the so-called "Phase 2" of NSTP 1 that I never knew should happen. I heard from my classmates, friends, blockmates that there was no such "Phase 2" when they finished their NSTP 1 class. Well, it just so happens that we take this phase as a mandatory requirement to pass this subject.</p><p>There\'s no problem with the influx of Phase 2 for Finals. It focuses on improving one\'s reading, writing, speaking and listening skills through English. I get to write more often in school than at home to utilize this skill in the near future.</p>',
            'date_published' => '2013-08-05',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'NSTP 1 Day 2',
            'description' => '<p>Here is another day in my NSTP 1 class. Honestly, I felt a weird vigor in me that my assignment (to be passed later on) is wrong because I pasted the picture immediately at my journal (seen on the previous page) when I\'m supposed to just bring the picture instead. That\'s what I thought until the class started wherein our instructor asked who did the assignment.</p><p>Then he asked those who didn\'t do the assignment. Luckily, I was able to accomplish it in the wee hours of the morning. Hard to look for the picture you need due to tiredness,boredom and that urge to sleep and forget about the assignment. However, that wasn\'t the case. I had to fight that sleepiness and accomplish the assignment before going to bed. So I did. Found a newspaper with a lot of pictures of gadgets, technologies etc. and made haste in cutting that newspaper to nab the picture I need.</p><p>I pity those who didn\'t do the assignment. One by one, they were asked to explain why. Some were forgiven while the others had more explaining to do. Standing up in the stage explaining something to save your face and be given consideration seems hard for those students. Well at least those who were given a chance gets to do the assignment.</p><p>Our instructor then revealed what we are going to do with the pasted picture. We had to give a description about it. Seems easy? Somewhat. We had to use 2 paragraphs minimum to fulfill the task. That\'s quite absurd since I prefer just giving it a very simple, yet short description and "direct to the point" rather than an extensive, yet long description that doesn\'t get to the point of what you\'re trying to imply. I did it with as less sentences as possible yet concise.</p><p>Afterwards, we had to select a partner who\'ll be asking questions regarding the description we wrote. This is to prepare for Q &amp; A portions that our instructor will do when activities like this is given, in which he himself told the class awhile ago. Then my partner has to sign it as proof that she was my partner for this activity.</p><p>Next meeting, students will be randomly called and read the description he/she wrote. The Q &amp; A portion comes afterward, The instructor will ask questions based on the description the student read.</p>',
            'date_published' => '2013-08-07',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'End of Ramadan',
            'description' => '<p>No classes today. It is the end of the Ramadan for our Muslim brothers and sisters. I guess that means the activity will continue by Monday. Woo! Nice one. Thank you. Much respect to these people. If we had classes today, I bet everyone would get bored. Haha. Luckily, we didn\'t have any.</p>',
            'date_published' => '2013-08-09',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Strong Typhoons',
            'description' => '<p>No classes once again due to a strong typhoon called Labuyo. The typhoon has been a nuisance lately since its entry to the country, destroying agriculture, homes, lives and sources of income. That\'s a terrible thing about typhoons. We rejoice when there are no classes whereas others are unfortunate because they are victims of flash floods, land erosion and other similar calamities brought about by typhoons.</p>',
            'date_published' => '2013-08-12',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'UC Los Angeles',
            'description' => '<p>Came back to class after yet another long weekend. So we get to meet another instructor today. Last week, we met our first instructor coming from the College of Arts &amp; Science (CAS) department who specializes in the English division. Today, our second instructor comes from the "UC-LA". I even joked around, thinking that UC is in LA (Los Angeles), a state in America. But no, it actually stands for "University of the Cordilleras - Legarda Annex". Seems legit.</p><p>Here\'s something good about him. He speaks French fluently. Very unique personality. Energetic and charismatic, he tries to make us awake with that energy and charisma he possessed. He even shared some French words such as "thank you very much", "hello", "good afternoon" etc. (although I forgot what the actual words were, they sounded really different).</p><p>So we had to present today the activity that was originally slated for Monday but there were no classes due to typhoon Labuyo\'s onslaught. It was by group and we had to name our groups. We named it after the typhoon. Then one representative from each group to read what he wrote for activity #1. In our group, I was chosen to be the representative. I was nagged by my best friend. Haha. Didn\'t have a choice. After the representatives read their activity #1 output, an open forum &amp; feedback part was included. They had to give comments to each group. The comment given to me was "it was nice and straight to the point but it was so short. Overall, it was okay.". Afterwards, the instructors discussed on properly introducing oneself/someone then gave another activity regarding that. We have to make our self-introduction speech and present that next meeting. I doubt all of us will present anyway.</p>',
            'date_published' => '2013-08-14',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'NSTP 1 Output Presentations!',
            'description' => '<p>So we had to present our outputs today. Yes, I was right. The instructors couldn\'t accommodate the time for all of us to present so they decided once again to have representatives from each group to represent their groups and deliver the output. Good idea. One group presented a duo conversation, the other a "new acquaintance" conversation and the last a duo conversation as well. Our group\'s representatives, however, presented a group conversation. There were a lot of them, luckily sir said it was fine.</p><p>After the presentations, our English instructor discussed UC\'s Core Values. I don\'t know why they give too much importance on that. Those are the things I see on bulletin boards all over the campus. They are self-explanatory, in my opinion.</p><p>After her discussion, another activity took place. We had to give phrases we like about UC, phrases we don\'t like about UC and phrases that UC can improve on. Not only that we need to use 15 nouns, verbs and adverbs, 10 adjectives, 20 prepositions and conjunctions. After that, using those phrases, we must construct an essay about UC. That\'s a hassle. We only had 30 minutes to do that but thankfully it was by group of 3\'s. We were the second-to-the-last group to pass. It was hard. I did the essay writing while my 2 groupmates formed the phrases. Thank God that was over!</p>',
            'date_published' => '2013-08-16',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Exhausting Day',
            'description' => '<p>It\'s been 11 months since I wrote an entry here. Feels good to write again. Yesterday, there were no classes since it was Cordillera Day. I thought there would be classes today only to find out classes are suspended due to typhoon Glenda. I never stepped inside UC even. For most, it\'s good. As for me, it\'s not. I shouldn\'t stepped out of the jeep if I knew this would happen. A waste of jeepney fare. I decided to go home.</p><p>On my way, I saw my classmate Zedrick go to school. Ironic because he rarely attends classes and decided to attend when classes were suspended. Haha! I told him classes were suspended and he was very surprised. I convinced him to go and play at TNC in SLU since it was too early to go home. So we did and played League of Legends. Another frustration came in. There was a brownout mid-game. It\'s annoying, we were winning already. Everyone stepped out and we decided to eat at a nearby carinderia. I ordered… Pork Sisig! Yummy. Zedrick ordered a Thai-style chicken. Yeah, it was delicious too!</p><p>We decided to part way and went home. There was an electricity. Very ironic. This day was fooling me.</p>',
            'date_published' => '2014-07-16',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Jogging, Exams, and Dates',
            'description' => '<p>A cold, rainy day surprises my Sunday morning. The planned jogging didn\'t push through. We were supposed to jog at 5:30 AM but we failed to wake up earlier. Me and Ate Kamille enjoyed the cold morning and decided to sleep more. I woke up at around 9 AM. Not the usual time I wake up. Haha!</p><p>This is my only day to review for Midterm exams starting tomorrow, Monday. Tomorrow\'s exam will be OOP 1 and Network Fundamentals. Laboratory and Lecture in one day. I fear my Network Fundamentals class. Questions will be arguably tougher than the Prelim exam. OOP 1\'s lecture is easy but the lab will be harder. Well, goodluck tomorrow then.</p><p>I texted Kaye and I said that we should have a date today. The plan is set in the afternoon. I wore my personally bought CM Punk\'s Best In The World (black) shirt, gray Tribal jeans bought by Daddy, a personally bought gray Vans sneakers and a pair of white Dickies socks. I went to Kaye\'s boarding house at Sandico St. (below Cathedral) only to find out she just woke up. I patiently waited for 30 minutes, just roaming around the parking area. Then I saw her coming out, she was sizzling hot! Happiness. Sadly, we fought at Vizco\'s (my first time to eat there) because I was laughing at her. It wasn\'t a negative joke but she deemed it negative. I won\'t say something that would hurt her. I just didn\'t realize she would take it seriously. She made me cry because she wouldn\'t hear my side, that she always must be right and I couldn\'t justify my own actions. By her request, she wants to be alone and I need to leave. When I got home, I cried really hard. Then I took a nap silently.</p>',
            'date_published' => '2014-08-03',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'The Breakdown',
            'description' => '<p>Today is Monday and marks the 10th week of the current trimester. Finally, we started our Finals lecture on OOP 1. A delayed meeting is precious enough to catch up with discussions since there\'s less than a month left and its the Final exams already. It\'s too fast!</p><p>I was supposed to see Kaye today but she didn\'t allow me since her "roommate" is there. I respect her privacy but I felt like she just doesn\'t want to see me today. Quite sad to know that I can\'t go to the boarding house. Then she asked me (via text) if I\'m having a hard time with her, how my efforts go in vain and she doesn\'t reciprocate it, how the relationship affects my studies, how unfair that she doesn\'t give an attention to me, that she couldn\'t reply back to me and if I am already "tired". I asked her if she still loves me and she said "Kaw ba?". She didn\'t really answer it. When I said "Love you baby.", she replied "Ok.. sige kain ka na. Ingat.". I still have to ask about it so she would say she loves me too. From this point, I felt sad. Moreover, when I made it to Loakan terminal, I called her and she said they\'re in a taxi going to Loakan already. I told her to text me before she leaves. She probably just didn\'t care how I persevered waiting for her.</p><p>I decided to print 8 photos of me and Kaye. While I was waiting, tears ran down my face. I went home and immediately sat on the couch. Never before had I cried that emotionally when she can\'t say she loves me. I had to ask her before she can say it without being told to. One day, she would realize that even in my busy classes, I care, I pity and I love her no matter what happens. It truly pains me that she wouldn\'t talk to me the way she used to do before.</p>',
            'date_published' => '2014-08-11',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Early Bird Catches the Worm',
            'description' => '<p>Woke up at around 4:12 AM. Had around a 6-hr sleep. I scheduled this day for my jogging since I need to be innovative and creative during my 3 weeks of semestral break. Heated my water, took a bath, dressed up, had wax, prepared the items to bring (wallet, face towel, cellphone, perfume, water) and placed them in a Reebok bag that Ate Kamille lent me temporarily.</p><p>Time check at 5:26 AM. Got the house keys and carried my bag. Ooohh, it\'s not a cold morning (when it\'s supposed to be). I hardly feel anything on my Kevin Garnett Adidas shoes. Anyway, I made it to Burnham and had a warm-up by the Children\'s Park going to the Biking Area and to the Lake, at last. I was there just in time for 6 AM (the bell from City Hall rang) and jogged around the Lake for 9 laps. That beats my July 29 record with only 8 1/2 laps. Black shoes are better than white ones haha.</p><p>After jogging, I went to TNC only to find out it was closed. Well, I can\'t play today haha. Despite that, I enjoyed so much.</p>',
            'date_published' => '2014-09-07',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Good Food, Bad Brother',
            'description' => '<p>A cold breeze greets my Thursday morning. I was able to prepare and eat breakfast in less than an hour since I\'m rushing to my 7:30 class (Applied Data Structures). The jeep filled up so fast, expecting to arrive in UC in time. I realized I was too early because Ma\'am Cruz isn\'t around yet. As expected, Ricky, Kendrick and Jhon didn\'t attend class again. Paul came around 8:30 AM, 25 minutes before the time. At least he still bothered to enter class. But it was vice-versa for Communication in the Workplace: Paul was absent and the 3 friends were present. So ironic.</p><p>After my Communication in the Workplace class, I waited for Ate Kamille and Ate Collen at 4:30 PM. We took a jeepney ride going to SLU (Ate Kamille and Ate Collen reminisces their tenure in SLU) and ate at Macsbox. I tried the Porky Pick rice and went for another round this time, ordered Crab Sticks rice. Delicious!</p><p>We went home then I ate Sopas, to make it up to Tita Vina who was expecting me since 4 PM (I wasn\'t able to text her). Again, I exclaimed inside my head "Delicious!". It is worth eating for. Mama called to ask how everything is going on the house. I said our woes with Patrick such as disrespecting our relatives, not eating meals on time, hard to call and the worst of them all, not attending classes. Oh for Pete\'s sake, Daddy should fund his schooling (personal needs too) when Patrick studies in Manila. He\'s a pain in the ass! I always get concerns from Tatay and Tita Vina about his laziness. I\'m already ashamed of answering their questions everytime they open it. Damn, my life is full of lies and problems!</p>',
            'date_published' => '2014-11-13',
        ]);
        Post::create([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'A Night to Remember',
            'description' => '<p>Kamile invited me to go out for a party at Spade Superclub a couple of days ago. I wore a plain black V-neck shirt, a Wall Street brown windbreaker, a Cotton Crews brown pants and the Converse sneakers. Also included is an undercut hairstyle, waxed by Bench. This is going to be a cold, cold night!</p><p>I arrived at Spade before 10 PM. I thought I was late since the agreement was 9:30 PM but it was exactly the opposite - I was early! I waited for around 40 minutes and they finally arrived. This is the 3rd time I\'ve been to Spade with her. March 29 was the first one, which was a CEA Night. August 20 was the second one, which was her 20th birthday and VIP inclusion.</p><p>Once inside, we ordered the first 2 buckets of San Mig Light worth P119 each - my treat but I assure them they\'ll pay the succeeding buckets because they didn\'t bother sharing their payment. Why go to a bar if you can\'t have your share for the beers?</p><p>Ange volunteered to pay for the 3rd bucket and asked me to bring it to our tables. I had to traverse the crowded space with ease since I was holding the bucket. Then some of Kamile\'s friends asked her if I\'m her boyfriend (how I\'d wish!). I said no since I heard them. This is one of the best moments of my night.</p><p>Two more of her friends came in. They\'re sisters! I socialized with one of them. Her name is Daphne. She wore a red cocktail dress, has semi-long hair and brought with her a small purse. At first, I thought she\'s sexy but as I gradually remember her face, she\'s not that sexy. Two foreigners came to our table. One of them is dancing with her. Then Daphne approached me and said she doesn\'t like it. So she talked to that foreigner and told him I\'m her boyfriend before telling it to me. It was her way to stop that guy from dancing with her. I just reluctantly agreed so I deemed her my "girlfriend of the night". It was just temporary! Haha. Another best moment of the night.</p><p>Ange then talked to me and asked if we would order a vodka (because the others aren\'t giving their share) or order another bucket instead. We went for Jose Cuervo (it was the cheapest one in the menu) and finally, the others gave their share. Kamile and Dave left during this time. Two of their friends left shortly afterwards. By this time, I socialized with one of the girls that was left behind.</p><p>Her name is Krizia. She used to be Kamile\'s classmate but transferred to UB taking up HRM. She asked me to keep the guy behind her at bay because she doesn\'t know him and he\'s not even part of our group. I convinced that person and he slowly went away. Good boy. We left Spade at around 4 AM. Daphne and her sister went home. This leaves 5 of us remaining. We walked going to the convenience store in Petron for relaxing since we got deaf due to the prolonged stay inside Spade. Ange was also drunk and was dancing dirty to me. It\'s a secret to not tell it to anyone we both know. I treated Krizia the Sola drink. We sat by the wooden bench and chatted until Ange, Mike (Ange\'s bestfriend) and one of Kamile\'s batchmate left. This leaves me and Krizia.</p><p>We didn\'t want to go home yet so we decided to chat in Rose Garden until sunlight. We got there by 5 AM. She has a crush on me! While chatting, I\'m so clingy and romantic to her (like holding her hands, wrapping my arms on her shoulder) as if she\'s my girlfriend. She\'s kind, funny and cute. At 6 AM, I hailed a cab for her and got her number (09174298800). Last best moment of the night. This is my best day for 2014!</p>',
            'date_published' => '2014-12-13',
        ]);

        // Poems #s 1 ~ 8
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'A Family I Called',
            'description' => '<p>I.</p><p>A family is a group of people.</p><p>A cohesive unit that\'s hard to topple.</p><p>United we stand, divided we fall.</p><p>Nothing stops us like a fly on the wall.</p><p>II.</p><p>We\'re a close-knit family I\'m fond</p><p>It\'s glued together like Mighty Bond</p><p>We help each other at all times</p><p>Ready for the next day as it chimes</p><p>III.</p><p>Though my parents are always far, far away</p><p>They sacrifice their blood, sweat and tears each day</p><p>We study as they work in the background</p><p>Coming home, this is where our love is found</p><p>IV.</p><p>My goal is to become successful</p><p>To bring home earnings so bountiful</p><p>Obstacles to face that are untold</p><p>To repay hard work of my family a hundredfold</p><p></p><p>Taken from my Values subject. The title is preset. SW #2 20/20</p>',
            'date_published' => '2011-09-09',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'Widespread Cheaters',
            'description' => '<p>I.</p><p>Cheaters are not ashamed</p><p>That their cheats ruin our game</p><p>They take advantage over other players</p><p>We hunt them down and so we are their slayers</p><p></p><p>II.</p><p>They are very quiet and very serious</p><p>Their 1-hit kill is what makes them mysterious</p><p>All hell will break loose</p><p>When those cheats are overused</p><p></p><p>III.</p><p>Some players stick to their ally cheaters</p><p>While those who don\'t, are now their haters</p><p>They want the cheaters to get kicked</p><p>If they don\'t, they will be called weak</p><p></p><p>IV.</p><p>The match ends, cheaters leave the room</p><p>Before they get dusted with a broom</p><p>They showed no mercy while in-game</p><p>By doing so, they ruined their name</p><p></p><p>This poem is based on the rampant cheating (aka cheating pandemic) on the online FPS game called Crossfire PH (CF Philippines)</p>',
            'date_published' => '2011-09-28',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'CM Punk\'s Uprising',
            'description' => '<p>I.</p><p>He claims to be a hero in our TV</p><p>He calls himself the Cult of Personality</p><p>His in-ring mic skills are devastating</p><p>Sending fans all over the world flying</p><p></p><p>II.</p><p>It started when he blasted the WWE\'s treatment of him</p><p>Vowing to leave the WWE with the title in a whim</p><p>He became #1 Contender by beating Rey Mysterio</p><p>Including the arrogant and destined Alberto Del Rio</p><p></p><p>III.</p><p>He won the WWE title on his final night</p><p>Alberto rushed in but wasn\'t able to fight</p><p>Punk fled from the crowd with the title</p><p>While Vince can only do so little</p><p></p><p>IV.</p><p>Kevin Nash returned and cost Punk his title match</p><p>Alberto cashed in and won Punk\'s title he snatched</p><p>Triple H was shocked at the sudden turn of events</p><p>His position as COO, started with huge dents</p>',
            'date_published' => '2011-10-11',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'The Golden Sun',
            'description' => '<p>By: Jenica Cayabyab and Tristan Pullido</p><p></p><p>I.</p><p>The golden sun gently peeps</p><p>Among the green towering mountains</p><p>As its rays reflect and slowly leaps</p><p>On mother nature who had just awaken</p><p></p><p>II.</p><p>The birds sing such sweet music</p><p>While trees dance in a cheerful song</p><p>And the waves collide really quick</p><p>That they run back to the peaceful horizon</p><p></p><p>III.</p><p>One could close his eyes to be welcomed again</p><p>By a paradise as perfect as Eden</p><p>No one would have thought this will be by then</p><p>Of man\'s technologies, a dumpsite not garden</p><p></p><p>IV.</p><p>Buildings made on flattened mountains</p><p>Vehicles eat on energy below</p><p>Blue skies filled with gray stains</p><p>Gray air, dirty to everyone, a no-no</p><p></p><p>V.</p><p>The plastics that harm the smallest lands</p><p>Chemicals that kill these young, little plants</p><p>All from man\'s curious but deadly hands</p><p>The product of all our selfish wants</p><p>VI.</p><p>Aren\'t we one of God\'s creations</p><p>Along with nature, earth and everything?</p><p>We weren\'t made to bring divisions</p><p>We were born to take care of all God\'s beings</p><p></p><p>VII.</p><p>We were happy, everyone was</p><p>Simply by just wandering inside nature</p><p>Simply by lying down the thick, green grass</p><p>Simply by living without destroying pastures</p><p></p><p>VIII.</p><p>Yes, indeed, many of us have been in cases</p><p>Strived to bring back what nature was before</p><p>The rest needs reality to crash to their faces</p><p>Before nature actually shuts its long-opened door</p>',
            'date_published' => '2011-10-17',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'Salvation',
            'description' => '<p>By: Troy Mascenon and Ida Caja</p><p></p><p>I.</p><p>Our world is a beautiful place you see</p><p>Man and nature living in harmony</p><p>Breath-taking wonders and heart-racing views</p><p>That fills you with joy and makes you feel new</p><p></p><p>II.</p><p>The clouds so fluffy, the sky so smooth</p><p>The light that guides you in every move</p><p>The trees so sturdy, the winds so fresh</p><p>Thanks God, you\'re the best!</p><p></p><p>III.</p><p>And God says "Anything for you"</p><p>I wonder how I can see God in full view</p><p>I pinch myself, no this isn\'t a dream</p><p>I\'m in heaven now, that\'s what it seems</p><p></p><p>IV.</p><p>God showed the world that I used to live in</p><p>Full of greed, full of wrath, full of sin</p><p>The air is filled with smoke, darkness fills the air</p><p>The water is polluted, life is hard to bear</p><p></p><p>V.</p><p>"This is all your fault" God exclaimed out loud</p><p>"You took everything for granted, brought yourselves down"</p><p>"But I love you, I\'ll keep you safe and warm"</p><p>"No matter what faults, I\'ll take you away from harm"</p>',
            'date_published' => '2011-10-17',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'The Big Difference',
            'description' => '<p>By: Sharmaine Modgil and Kristine Prieto</p><p></p><p>I.</p><p>I remember the cold breeze</p><p>When it brushed my hair with ease</p><p>As I sat under an old tree</p><p>But now, what do I see?</p><p></p><p>II.</p><p>Now, what can I say?</p><p>As I wake up day by day</p><p>This feeling I have is strange</p><p>Now that everything has changed</p><p></p><p>III.</p><p>I used to watch the sun as it sets</p><p>Now, I\'m simply filled with regrets</p><p>I remember the waves crash so gently</p><p>But now, everything\'s depleting silently</p><p></p><p>IV.</p><p>I missed the high mountains I climbed</p><p>On the peak, joy is what I find</p><p>The sound of the waterfalls I used to hear</p><p>But now, the end is almost near</p><p></p><p>V.</p><p>Now I see the big difference</p><p>Only if we all come to our sense</p><p>We must start the change now</p><p>We must find a way how</p>',
            'date_published' => '2011-10-17',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'Filthy Atmosphere',
            'description' => '<p>By: Kevin Decena and Lianne Ritos</p><p></p><p>I.</p><p>People throw their garbages everywhere</p><p>In a park, in the streets, almost anywhere</p><p>We live in an environment so filthy</p><p>Sad to say, not everyone is guilty</p><p></p><p>II.</p><p>Throwing garbages anywhere, we had so many lies</p><p>While on the other hand, it is our nature that cries</p><p>Our environment already has huge dents</p><p>As air pollution come through our vents</p><p></p><p>III.</p><p>Underwater, it is the aquatic animals we affect</p><p>When harmed, the creatures leave a horrendous effect</p><p>Innocent lives are lost due to selfish motive</p><p>Only giving them limited days to live</p><p></p><p>IV.</p><p>We don\'t breath clean and fresh air</p><p>Some people tend to make lives unfair</p><p>There are garbages that are being burned</p><p>When all hope is gone, it\'ll be a lesson learned</p><p></p><p>V.</p><p>Despite the condition of our environment</p><p>So little actions were done by our government</p><p>We must act before it\'s too late</p><p>Before mother nature decides our fate</p>',
            'date_published' => '2011-10-17',
        ]);
        Post::create([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'Oblivion',
            'description' => '<p>I.</p><p>I live in a world of lies</p><p>The light fades as daylight dies</p><p>Time ticks life away while nature cries</p><p>Oblivion is near as humanity had many tries</p><p></p><p>II.</p><p>Our hearts so hard like a rock</p><p>To other people whom we mock</p><p>Yet we pretend we are in shock</p><p>Forgiveness from us is so hard to unlock</p><p></p><p>III.</p><p>Wrath engulfs our hearts so deep</p><p>Hatred sinks our minds as it creep</p><p>A blank expression from others as they weep</p><p>Pray the Lord my soul to keep</p><p></p><p>IV.</p><p>Million cries will birth a new balance</p><p>In the future, we may not stand a chance</p><p>We don\'t know if it\'s our last dance</p><p>Right now, we must make haste in advance</p><p></p><p>V.</p><p>Darkness fills the atmosphere and the end draws near</p><p>Showering rain of chaos, crippled with fear</p><p>So much pain and agony I always hear</p><p>Take a minute to look around you, my dear</p>',
            'date_published' => '2011-12-09',
        ]);
    }
}
