<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 
'/WITHHELD/Exception.php';
require 
'/WITHHELD/PHPMailer.php';
require '/WITHHELD/SMTP.php';

$mail = new PHPMailer(true);
$mail2 = new PHPMailer(true);
$r_servername = 'WITHHELD';
$r_username = 'WITHHELD';
$r_password = 'WITHHELD';
$r_databasename = 'WITHHELD';

$conn = new mysqli($r_servername, $r_username, $r_password, $r_databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query_a = 'SELECT * FROM `WITHHELD` ORDER BY a_id;';
$query_db = 'SELECT * FROM `WITHHELD` ORDER BY uniq_id DESC LIMIT 1;';

$result_a = $conn->query($query_a);
$result_dbc = $conn->query($query_db);

$row_dbc = $result_dbc->fetch_assoc();

$code = $row_dbc['code'];

$agent_email = 'na';

while($row_a = $result_a->fetch_assoc())
{
    $code1 = $row_a['a_code1'];
    $code2 = $row_a['a_code2'];    

    if (($code1 == $code) || $code2 == $code)
    {
        $agent_email = $row_a['a_email'];
        $agent_code = $row_a['a_code1'];
    }
}

$result_db = $conn->query($query_db);

while($row_db = $result_db->fetch_assoc())
{
    try {
        $variables = array();
        $variables['uniq_id'] = $row_db['uniq_id'];

        if ($row_db['code'] == '')
        {
            $variables['code'] = 'n/a';
        }
        else
        {
            $variables['code'] = $row_db['code'];
        }

        $variables['p_fname'] = $row_db['p_fname'];
        $variables['p_lname'] = $row_db['p_lname'];
        $variables['p_email'] = $row_db['p_email'];
        $variables['p_mobile'] = $row_db['p_mobile'];
        $variables['p_address'] = $row_db['p_address'];

        if ($row_db['p_unitnum'] == '')
        {
            $variables['p_unitnum'] = 'n/a';
        }
        else
        {
            $variables['p_unitnum'] = $row_db['p_unitnum'];
        }
        
        $variables['p_suburb'] = $row_db['p_suburb'];
        $variables['p_towncity'] = $row_db['p_towncity'];
        $variables['p_postcode'] = $row_db['p_postcode'];

        switch ($row_db['p_ownertenanted'])
        {
            case 'property_1':
                $variables['p_ownertenanted'] = 'Owner Occupied';
                break;
            case 'property_2':
                $variables['p_ownertenanted'] = 'Tenanted';
                break;
            case 'property_3':
                $variables['p_ownertenanted'] = 'Vacant';
                break;
        }

        switch ($row_db['p_propertycondition'])
        {
            case 'condition_1':
                $variables['p_propertycondition'] = 'Run down and needs work';
                break;
            case 'condition_2':
                $variables['p_propertycondition'] = 'Average but neat and tidy';
                break;
            case 'condition_3':
                $variables['p_propertycondition'] = 'Good/clean/fresh, but a little dated';
                break;
            case 'condition_4':
                $variables['p_propertycondition'] = 'Very good, renovated, but mostly new';
                break;
            case 'condition_5':
                $variables['p_propertycondition'] = 'Excellent, brand new, or fully renovated';
                break;
        }

        switch ($row_db['p_seaviews'])
        {
            case 0:
                $variables['p_seaviews'] = 'No';
                break;
            case 1:
                $variables['p_seaviews'] = 'Yes';
                break;
        }

        switch ($row_db['p_cityviews'])
        {
            case 0:
                $variables['p_cityviews'] = 'No';
                break;
            case 1:
                $variables['p_cityviews'] = 'Yes';
                break;
        }

        switch ($row_db['p_ruralviews'])
        {
            case 0:
                $variables['p_ruralviews'] = 'No';
                break;
            case 1:
                $variables['p_ruralviews'] = 'Yes';
                break;
        }

        switch ($row_db['p_landscapedgardens'])
        {
            case 0:
                $variables['p_landscapedgardens'] = 'No';
                break;
            case 1:
                $variables['p_landscapedgardens'] = 'Yes';
                break;
        }

        switch ($row_db['p_indoorfire'])
        {
            case 0:
                $variables['p_indoorfire'] = 'No';
                break;
            case 1:
                $variables['p_indoorfire'] = 'Yes';
                break;
        }

        switch ($row_db['p_outdoorfire'])
        {
            case 0:
                $variables['p_outdoorfire'] = 'No';
                break;
            case 1:
                $variables['p_outdoorfire'] = 'Yes';
                break;
        }

        switch ($row_db['p_alarm'])
        {
            case 0:
                $variables['p_alarm'] = 'No';
                break;
            case 1:
                $variables['p_alarm'] = 'Yes';
                break;
        }

        switch ($row_db['p_heatpump'])
        {
            case 0:
                $variables['p_heatpump'] = 'No';
                break;
            case 1:
                $variables['p_heatpump'] = 'Yes';
                break;
        }

        switch ($row_db['p_heatrecoverysys'])
        {
            case 0:
                $variables['p_heatrecoverysys'] = 'No';
                break;
            case 1:
                $variables['p_heatrecoverysys'] = 'Yes';
                break;
        }

        switch ($row_db['p_tenniscourt'])
        {
            case 0:
                $variables['p_tenniscourt'] = 'No';
                break;
            case 1:
                $variables['p_tenniscourt'] = 'Yes';
                break;
        }

        switch ($row_db['p_swimmingpool'])
        {
            case 0:
                $variables['p_swimmingpool'] = 'No';
                break;
            case 1:
                $variables['p_swimmingpool'] = 'Yes';
                break;
        }

        switch ($row_db['p_spapool'])
        {
            case 0:
                $variables['p_spapool'] = 'No';
                break;
            case 1:
                $variables['p_spapool'] = 'Yes';
                break;
        }

        switch ($row_db['p_sauna'])
        {
            case 0:
                $variables['p_sauna'] = 'No';
                break;
            case 1:
                $variables['p_sauna'] = 'Yes';
                break;
        }

        switch ($row_db['p_bbqarea'])
        {
            case 0:
                $variables['p_bbqarea'] = 'No';
                break;
            case 1:
                $variables['p_bbqarea'] = 'Yes';
                break;
        }

        switch ($row_db['p_bedrooms'])
        {
            case 'bedroom_1':
                $variables['p_bedrooms'] = '1';
                break;
            case 'bedroom_2':
                $variables['p_bedrooms'] = '2';
                break;
            case 'bedroom_3':
                $variables['p_bedrooms'] = '3';
                break;
            case 'bedroom_4':
                $variables['p_bedrooms'] = '4';
                break;
            case 'bedroom_5':
                $variables['p_bedrooms'] = '5';
                break;
            case 'bedroom_6':
                $variables['p_bedrooms'] = '6';
                break;
            case 'bedroom_7':
                $variables['p_bedrooms'] = '7';
                break;      
        }

        switch ($row_db['p_bathrooms'])
        {
            case 'bathrooms_1':
                $variables['p_bathrooms'] = '1';
                break;
            case 'bathrooms_2':
                $variables['p_bathrooms'] = '2';
                break;
            case 'bathrooms_3':
                $variables['p_bathrooms'] = '3';
                break;
            case 'bathrooms_4':
                $variables['p_bathrooms'] = '4';
                break;
        }

        switch ($row_db['p_seperatetoilets'])
        {
            case 'toilets_1':
                $variables['p_seperatetoilets'] = '1';
                break;
            case 'toilets_2':
                $variables['p_seperatetoilets'] = '2';
                break;
            case 'toilets_3':
                $variables['p_seperatetoilets'] = '3';
                break;
            case 'toilets_4':
                $variables['p_seperatetoilets'] = '4';
                break;
            case 'toilets_5':
                $variables['p_seperatetoilets'] = '5';
                break;
            case 'toilets_6':
                $variables['p_seperatetoilets'] = '6';
                break;
            case 'toilets_7':
                $variables['p_seperatetoilets'] = '7';
                break;
        }

        switch ($row_db['p_parking'])
        {
            case 'parking_1':
                $variables['p_parking'] = 'None';
                break;
            case 'parking_2':
                $variables['p_parking'] = 'Off Street Only';
                break;
            case 'parking_3':
                $variables['p_parking'] = 'Single Carport';
                break;
            case 'parking_4':
                $variables['p_parking'] = 'Double Carport';
                break;
            case 'parking_5':
                $variables['p_parking'] = 'Single Garage';
                break;
            case 'parking_6':
                $variables['p_parking'] = 'Double Garage';
                break;
            case 'parking_7':
                $variables['p_parking'] = 'More';
                break;
        }

        switch ($row_db['p_propertytype'])
        {
            case 'property-type_1':
                $variables['p_propertytype'] = 'Freestanding House';
                break;
            case 'property-type_2':
                $variables['p_propertytype'] = 'Semi Detached House';
                break;
            case 'property-type_3':
                $variables['p_propertytype'] = 'Apartment/Unit';
                break;
            case 'property-type_4':
                $variables['p_propertytype'] = 'Terrace Townhouse';
                break;
            case 'property-type_5':
                $variables['p_propertytype'] = 'Duplex';
                break;
            case 'property-type_6':
                $variables['p_propertytype'] = 'Rural/Farm';
                break;
            case 'property-type_7':
                $variables['p_propertytype'] = 'Land Only';
                break;
        }

        switch ($row_db['p_numberoflevels'])
        {
            case 'levels_1':
                $variables['p_numberoflevels'] = '1';
                break;
            case 'levels_2':
                $variables['p_numberoflevels'] = '2';
                break;
            case 'levels_3':
                $variables['p_numberoflevels'] = '3';
                break;
            case 'levels_4':
                $variables['p_numberoflevels'] = '4';
                break;
        }

        switch ($row_db['p_ageofproperty'])
        {
            case 'property-age_1':
                $variables['p_age_of_property'] = 'After 2020';
                break;
            case 'property-age_2':
                $variables['p_age_of_property'] = '2010s';
                break;
            case 'property-age_3':
                $variables['p_age_of_property'] = '2000s';
                break;
            case 'property-age_4':
                $variables['p_age_of_property'] = '1990s';
                break;
            case 'property-age_5':
                $variables['p_age_of_property'] = '1980s';
                break;
            case 'property-age_6':
                $variables['p_age_of_property'] = '1970s';
                break;
            case 'property-age_7':
                $variables['p_age_of_property'] = '1960s';
                break;
            case 'property-age_8':
                $variables['p_age_of_property'] = '1950s';
                break;
            case 'property-age_9':
                $variables['p_age_of_property'] = '1940s';
                break;
            case 'property-age_10':
                $variables['p_age_of_property'] = '1930s';
                break;
            case 'property-age_11':
                $variables['p_age_of_property'] = '1920s and earlier';
                break;
        }

        switch ($row_db['p_sizeofsection'])
        {
            case 'section-size_1':
                $variables['p_sizeofsection'] = '200 - 400m2';
                break;
            case 'section-size_2':
                $variables['p_sizeofsection'] = '400 - 600m2';
                break;
            case 'section-size_3':
                $variables['p_sizeofsection'] = '600 - 800m2';
                break;
            case 'section-size_4':
                $variables['p_sizeofsection'] = '800 - 1000m2';
                break;
            case 'section-size_5':
                $variables['p_sizeofsection'] = '1/4 acre';
                break;
            case 'section-size_6':
                $variables['p_sizeofsection'] = '1/2 acre';
                break;
            case 'section-size_7':
                $variables['p_sizeofsection'] = '1 acre';
                break;
            case 'section-size_8':
                $variables['p_sizeofsection'] = '2 - 5 acres';
                break;
            case 'section-size_9':
                $variables['p_sizeofsection'] = '5 - 10 acres';
                break;
            case 'section-size_10':
                $variables['p_sizeofsection'] = '10 - 20 acres';
                break;
            case 'section-size_11':
                $variables['p_sizeofsection'] = '20 - 50 acres';
                break;
            case 'section-size_12':
                $variables['p_sizeofsection'] = '50+ acres';
                break;
        }

        switch ($row_db['p_estimatedvalue'])
        {
            case 'value_1':
                $variables['p_estimatedvalue'] = '$50,000 to $100,000';
                break;
            case 'value_2':
                $variables['p_estimatedvalue'] = '$100,000 to $140,000';
                break;
            case 'value_3':
                $variables['p_estimatedvalue'] = '$140,000 to $180,000';
                break;
            case 'value_4':
                $variables['p_estimatedvalue'] = '$180,000 to $220,000';
                break;
            case 'value_5':
                $variables['p_estimatedvalue'] = '$220,000 to $260,000';
                break;
            case 'value_6':
                $variables['p_estimatedvalue'] = '$260,000 to $300,000';
                break;
            case 'value_7':
                $variables['p_estimatedvalue'] = '$300,000 to $340,000';
                break;
            case 'value_8':
                $variables['p_estimatedvalue'] = '$340,000 to $380,000';
                break;
            case 'value_9':
                $variables['p_estimatedvalue'] = '$380,000 to $420,000';
                break;
            case 'value_10':
                $variables['p_estimatedvalue'] = '$420,000 to $460,000';
                break;
            case 'value_11':
                $variables['p_estimatedvalue'] = '$460,000 to $500,000';
                break;
            case 'value_12':
                $variables['p_estimatedvalue'] = '$500,000 to $540,000';
                break;
            case 'value_13':
                $variables['p_estimatedvalue'] = '$540,000 to $580,000';
                break;
            case 'value_14':
                $variables['p_estimatedvalue'] = '$580,000 to $620,000';
                break;
            case 'value_15':
                $variables['p_estimatedvalue'] = '$620,000 to $660,000';
                break;
            case 'value_16':
                $variables['p_estimatedvalue'] = '$660,000 to $700,000';
                break;
            case 'value_17':
                $variables['p_estimatedvalue'] = '$700,000 to $800,000';
                break;
            case 'value_18':
                $variables['p_estimatedvalue'] = '$800,000 to $900,000';
                break;
            case 'value_19':
                $variables['p_estimatedvalue'] = '$900,000 to $1,000,000';
                break;
            case 'value_20':
                $variables['p_estimatedvalue'] = '$1,000,000 to $1,250,000';
                break;
            case 'value_21':
                $variables['p_estimatedvalue'] = '$1,250,000 to $1,500,000';
                break;
            case 'value_22':
                $variables['p_estimatedvalue'] = '$1,500,000 to $1,750,000';
                break;
            case 'value_23':
                $variables['p_estimatedvalue'] = '$1,750,000 to $2,000,000';
                break;
            case 'value_24':
                $variables['p_estimatedvalue'] = '$2,000,000 plus';
                break;
        }

        switch ($row_db['p_selltimeframe'])
        {
            case 'time-frame_1':
                $variables['p_selltimeframe'] = '0 - 3 months';
                break;
            case 'time-frame_2':
                $variables['p_selltimeframe'] = '3 - 6 months';
                break;
            case 'time-frame_3':
                $variables['p_selltimeframe'] = '6 months to a year';
                break;
            case 'time-frame_4':
                $variables['p_selltimeframe'] = '1 - 3 years';
                break;
            case 'time-frame_5':
                $variables['p_selltimeframe'] = '3 - 5 years';
                break;
        }


        $file_var = array();
            $file_var['APPRAISAL_ID'] = 'APPRAISAL_ID';
            $file_var['CODE'] = 'CODE';
            $file_var['FIRST_NAME'] = 'FIRST_NAME';
            $file_var['LAST_NAME'] = 'LAST_NAME';
            $file_var['EMAIL'] = 'EMAIL';
            $file_var['MOBILE'] = 'MOBILE';
            $file_var['ADDRESS'] = 'ADDRESS';
            $file_var['UNIT_NUM'] = 'UNIT_NUM';
            $file_var['SUBURB'] = 'SUBURB';
            $file_var['TOWN_CITY'] = 'TOWN_CITY';
            $file_var['POSTCODE'] = 'POSTCODE';
            $file_var['PROPERTY_OCCUPANCY'] = 'PROPERTY_OCCUPANCY';
            $file_var['PROPERTY_CONDITION'] = 'PROPERTY_CONDITION';
            $file_var['SEA_VIEWS'] = 'SEA_VIEWS';
            $file_var['CITY_VIEWS'] = 'CITY_VIEWS';
            $file_var['RURAL_VIEWS'] = 'RURAL_VIEWS';
            $file_var['LANDSCAPED_GARDENS'] = 'LANDSCAPED_GARDENS';
            $file_var['INDOOR_FIRE'] = 'INDOOR_FIRE';
            $file_var['OUTDOOR_FIRE'] = 'OUTDOOR_FIRE';
            $file_var['ALARM'] = 'ALARM';
            $file_var['HEATPUMP'] = 'HEATPUMP';
            $file_var['HEAT_RECOVERY_SYSTEM'] = 'HEAT_RECOVERY_SYSTEM';
            $file_var['TENNIS_COURT'] = 'TENNIS_COURT';
            $file_var['SWIMMING_POOL'] = 'SWIMMING_POOL';
            $file_var['SPA_POOL'] = 'SPA_POOL';
            $file_var['SAUNA'] = 'SAUNA';
            $file_var['BBQ_AREA'] = 'BBQ_AREA';
            $file_var['BEDROOMS'] = 'BEDROOMS';
            $file_var['BATHROOMS'] = 'BATHROOMS';
            $file_var['SEPERATE_TOILETS'] = 'SEPERATE_TOILETS';
            $file_var['PARKING'] = 'PARKING';
            $file_var['PROPERTY_TYPE'] = 'PROPERTY_TYPE';
            $file_var['NUMBER_OF_LEVELS'] = 'NUMBER_OF_LEVELS';
            $file_var['AGE_OF_PROPERTY'] = 'AGE_OF_PROPERTY';
            $file_var['SIZE_OF_SECTION'] = 'SIZE_OF_SECTION';
            $file_var['ESTIMATED_VALUE'] = 'ESTIMATED_VALUE';
            $file_var['SELL_TIME_FRAME'] = 'SELL_TIME_FRAME';


        if ($agent_email != 'na')
        {
            $appraisal_file1 = 'WITHHELD' . $agent_code . '_appraisals.csv';
            
            if (filesize($appraisal_file1) == 0)
            {
                $fp = fopen($appraisal_file1, 'w');
                fputcsv($fp, $file_var);
                fclose($fp);   
            }

            $f = fopen($appraisal_file1, 'a');
            fputcsv($f, $variables);
            fclose($f);
        }

        $appraisal_file2 = 'WITHHELD.csv';
            
        if (filesize($appraisal_file2) == 0)
        {
            $fpp = fopen($appraisal_file2, 'w');
            fputcsv($fpp, $file_var);
            fclose($fpp);   
        }

        $ff = fopen($appraisal_file2, 'a');
        fputcsv($ff, $variables);
        fclose($ff);


        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'WITHHELD';
        $mail->SMTPAuth = true;
        $mail->Username = 'WITHHELD'; 
        $mail->Password = 'WITHHELD';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
       
        $mail->setFrom($row_db['p_email'], $row_db['p_fname']. ' ' . $row_db['p_lname']);

        if ($agent_email == 'na')
        {
            $mail->addAddress('support@mypropertyisworth.nz', 'mypropertyisworth@gmail.com');
            $mail->addCC('mypropertyisworth@gmail.com');
            $mail->AddAttachment($appraisal_file2);
        }
        else
        {
            $mail->addAddress($agent_email);
            $mail->addBCC('support@mypropertyisworth.nz', 'mypropertyisworth@gmail.com');
            $mail->AddAttachment($appraisal_file1);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Appraisal ID: ' . $row_db['uniq_id']. ' - ' . $row_db['p_address'];
        
        $template = file_get_contents('/WITHHELD/appraisal_template.html');
        
        foreach($variables as $key=>$value)
        {
            $template = str_replace('{{ '.$key.' }}', $value, $template);
        }
        
        $mail->Body = $template;
        $mail->AltBody = 'Appraisal ID: ' . $variables['uniq_id']. '   Code: ' . $variables['code']. '   First Name: ' . $variables['p_fname']. '   Last Name: ' . $variables['p_lname']. '   Email: ' . $variables['p_email']. '   Mobile: ' . $variables['p_mobile']. '   Address: ' . $variables['p_address']. '   Unitnum: ' . $variables['p_unitnum']. '   Suburb: ' . $variables['p_suburb']. '   Town/City: ' . $variables['p_towncity']. '   Postcode: ' . $variables['p_postcode']. '   Property Occupancy: ' . $variables['p_ownertenanted']. '   Property Condition: ' . $variables['p_propertycondition']. '   Sea Views: ' . $variables['p_seaviews']. '   City Views: ' . $variables['p_cityviews']. '   Rural Views: ' . $variables['p_ruralviews']. '   Landscaped Gardens: ' . $variables['p_landscapedgardens']. '   Indoor Fire: ' . $variables['p_indoorfire']. '   Outdoor Fire: ' . $variables['p_outdoorfire']. '   Alarm: ' . $variables['p_alarm']. '   Heatpump: ' . $variables['p_heatpump']. '   Heat Recovery System: ' . $variables['p_heatrecoverysys']. '   Tennis Court: ' . $variables['p_tenniscourt']. '   Swimming Pool: ' . $variables['p_swimmingpool']. '   Spa Pool: ' . $variables['p_spapool']. '   Sauna: ' . $variables['p_sauna']. '   BBQ Area: ' . $variables['p_bbqarea']. '   Bedrooms: ' . $variables['p_bedrooms']. '   Bathrooms: ' . $variables['p_bathrooms']. '   Seperate Toilets: ' . $variables['p_seperatetoilets']. '   Parking: ' . $variables['p_parking']. '   Property Type: ' . $variables['p_propertytype']. '   Number of Levels: ' . $variables['p_numberoflevels']. '   Age of Property: ' . $variables['p_age_of_property']. '   Size of Section: ' . $variables['p_sizeofsection']. '   Estimated Value: ' . $variables['p_estimatedvalue']. '   Sell Time-Frame: ' . $variables['p_selltimeframe'];
       
       $mail->send();
        echo 'Message has been sent';
       
       } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: 
       {$mail->ErrorInfo}";
       }

       try {
        $mail2->SMTPDebug = 2;
        $mail2->isSMTP();
        $mail2->Host = 'WITHHELD';
        $mail2->SMTPAuth = true;
        $mail2->Username = 'WITHHELD'; 
        $mail2->Password = 'WITHHELD';
        $mail2->SMTPSecure = 'tls';
        $mail2->Port = 587;
       
        $mail2->setFrom('support@mypropertyisworth.nz', 'MyPropertyIsWorth');
        $mail2->addAddress($row_db['p_email']);
       
        $mail2->isHTML(true);
        $mail2->Subject = 'Appraisal Submission';
        
        $variables2 = array();
        $variables2['name'] = $row_db['p_fname'];
        
        $template2 = file_get_contents('/WITHHELD/confirmation.html');
        
        foreach($variables2 as $key=>$value)
        {
            $template2 = str_replace('{{ '.$key.' }}', $value, $template2);
        }
        
        $mail2->Body = $template2;
        $mail2->AltBody = "Thank you for taking the time to fill out our form. We have received your submission and will get back to you as soon as possible. If you have any further enquiries please don't hesitate to reply to this email or contact us using one of our social media links below.";

       $mail2->send();
        echo 'Message has been sent';
       
       } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: 
       {$mail2->ErrorInfo}";
       }
}

$conn->close();

?>