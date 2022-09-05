<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PageContentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('page_contents')->delete();

        \DB::table('page_contents')->insert(array (
            0 =>
            array (
                'id' => '11377bef-1ac2-11ec-b072-000c293f1073',
                'name' => 'Terms & Condition',
                'slug' => 'term-condition',
                'content' => '<b>Rajuri Steel</b> Please read this disclaimer carefully prior to use of Rajuri Steels website operated by M/s.Rajuri Steels, Jalna, Maharashtra. </br></br>The contents of this Company website are our intellectual property and as such you have to obtain our consent in writing before any reproduction or use of our content, even if you are including an acknowledgement of the source.</br></br>All information stated or cited herein is merely for informative and educational purpose. It cannot be interpreted as professional guidance for your construction activity. Should you decide to utilize or apply any information/recommendation on this website, you do so at your own risk.</br></br>By submitting any Feedback to the site, you accord us the right to use the Feedback without any restriction and without owing any compensation to you.</br></br>Rajuri Steels will not be liable for any special, consequential, or punitive damages, or any loss of revenues or profits, whether incurred directly or indirectly due to your access, use or inability to access or use the site. We are also not responsible for any loss of data, use, goodwill or any other intangible loss from the same.</p><p><strong>CEO</strong></p><h5>FOOD SERVICE COMPANY</h5><p>&nbsp;</p><p>See 750+ Happy Clients&#8217; Testimonials</p></div></div></div>',
                'createdAt' => '2021-09-21 15:25:35',
                'updatedAt' => '2022-02-16 13:24:31',
            ),
            1 =>
            array (
                'id' => '18214566-1ac2-11ec-b072-000c293f1073',
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<div class="container"> <div class="row"><div class="col-md-5 col-sm-5 col-sm-offset-1"><p>&#8220;Our expectations were high and we were not disappointed. RajuriSteel created a masterful design [and] invested the time to understand our business and audience and delivered a well thought-out design in very little time.&#8221;<br/><br/>Our expectations were high and we were not disappointed. RajuriSteel created a masterful design [and] invested the time to understand our business and audience and delivered a well thought-out design in very little time.&#8221;</p><p><strong>CEO</strong></p><h5>FOOD SERVICE COMPANY</h5><p>&nbsp;</p><p>See 750+ Happy Clients&#8217; Testimonials</p></div></div></div>',
                'createdAt' => '2021-09-21 15:25:47',
                'updatedAt' => '2022-02-16 13:40:33',
            ),
            2 =>
            array (
                'id' => 'a6a96855-9622-11ec-879c-000c293f1073',
                'name' => 'HR Policy',
                'slug' => 'hr-policy',
                'content' => '<h1> HR Policy </h1>',
                'createdAt' => '2022-02-25 15:36:51',
                'updatedAt' => '2022-02-25 15:36:51',
            ),
        ));


    }
}
