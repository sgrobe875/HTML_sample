<?php
include 'top.php';
//%^%^%^%^%^%^%^%^
//
print PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;

//
print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
//
// Initialize variables one for each form element
// in the order they appear on the form

$firstName = "";

$lastName = "";

$email = "sgrobe@uvm.edu";

$comments = "";

$visit = "Yes, recently";

$gluten = false;    //checked
$seafood = false;
$peanut = false; // not checked

$month = "January";

$day = "1";

//%^%^%^%^%^%^%^%^%^%^%^%^%^
//
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;
//
// Initialize Error Flags one for each form element we validate
// in the order they appear on the form
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$commentsERROR = false;
$visitERROR = false;
$birthdayError = false;

////%^%^%^%^%^%^%^%^%^%^%^%^%^
//
print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// have we mailed the information to the user, flag variable?
$mailed = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@
    //  
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // the url  for this form
    $thisURL = $domain . $phpSelf;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg .= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data -->' . PHP_EOL;
    // remove any potential JavaScript or html code from users input on the form.
    // Note it is best to follow the same order as declared in section 1c.
    
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    
    $comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");
    
    $visit = htmlentities($_POST["radVisit"], ENT_QUOTES, "UTF-8");
    
    if (isset($_POST["chkGluten"])) {
        $gluten = true;
    } else {
        $gluten = false;
    }
    
    if (isset($_POST["chkSeafood"])) {
        $seafood = true;
    } else {
        $seafood = false;
    }
    
    if (isset($_POST["chkPeanut"])) {
        $peanut = true;
    } else {
        $peanut = false;
    }
    
    $month = htmlentities($_POST["lstMonth"], ENT_QUOTES, "UTF-8");
    $day = htmlentities($_POST["lstDay"], ENT_QUOTES, "UTF-8");
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    //
    // Check each value for possible errors, empty or not what we expect. Need an IF block
    // for each element you will check. The if blocks should also be in the order that the
    // elements appear on your form so that the error messages will be in the order they 
    // appear. errorMsg will be displayed on the form see section 3b. The error flag 
    // ($emailERROR) will be used in section 3c.
    
    if($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have an extra character.";
        $firstNameERROR = true;
    }
    
    if($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have an extra character.";
        $lastNameERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }
    
    if ($comments != "") {
        if (!verifyAlphaNum($comments)) {
            $errorMsg[] = "Your comments appear to have extra characters that are not allowed.";
            $commentsERROR = true;
        }
    }
    if ($visit != "Yes, recently" AND $visit != "Yes, but not recently" AND $visit != "No") {
        $errorMsg[] = "Please indicate whether you have visited us or not.";
        $visitERROR = true;
    }
    
    if ($month == "" or $day == "") {
        $errorMsg = "Please enter your birthday";
        $birthdayError = true;
    }
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION 2d Process Form - Passed Validation -->'. PHP_EOL;
    //
    // Process for when the form passes validation (errorMsg array is empty)
    //
        if (!$errorMsg) {
            if($debug)
            print'<p>Form is valid</p>';
            
            
  

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
        //
        // Saves the data to a CSV file         
        // array used to hold form values that will be saved to a CSV file
            $dataRecord = array(); 
        // assign values to the dataRecord array
        $birthday = $month . ' ' . $day;
            
        $dataRecord[] = $firstName;
        $dataRecord[] = $lastName;
        $dataRecord[] = $email;
        $dataRecord[] = $birthday;
        $dataRecord[] = $visit;
        $dataRecord[] = $gluten;
        $dataRecord[] = $seafood;
        $dataRecord[] = $peanut;
        $dataRecord[] = $comments;
        
        // setup csv file
        $myFolder  = 'data/';
        $myFileName = 'registration';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;
        
        if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
        
        // open the file for append
        $file = fopen($filename, 'a');
        
        // write the forms information
        fputcsv($file, $dataRecord);
        
        // close the file
        fclose($file);
        
        
        
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g)
        
        $message = '<h2>Your information:</h2>';
        
        foreach ($_POST as $htmlName => $value) {
        
            $message .= '<p>';
            // breaks up the form names into words. for example
            // txtFirstName becomes First Name
            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));
            
            foreach ($camelCase as $oneWord) {
                $message .= $oneWord . ' ';
            }
        
            $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
            
        }
         
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f
        $to = $email; // the person who filled out the form
        $cc = '';
        $bcc = '';
        
        $from = 'The Summer House <sgrobe@uvm.edu>';
        
        // subject of mail should make sense to your form
        $subject = 'Thanks for signing up!';
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
        
        } // end form is valid
        
}    // ends if form was submitted.        
        

    
//###########################
//
print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;
//
?>
<main>
    <article>
        
<?php
    //###########################
    //
    print PHP_EOL . '<!-- SECTION 3a -->' . PHP_EOL;
    //
    // If it's the first time coming to the form or there are errors we are
    // going to display the form

    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
            print '<h2>Thank you for providing your information.</h2>';
            
            print'<p>For your records a copy of this data has ';
            if (!mailed) {
                print "not ";
            }
            
            print'been sent:</p>';
            print'<p>To: ' . $email . '</p>';
            
           print $message; 
    } else {
        print '<h2>Register Today!</h2>';
        print '<p class = "form-heading">Sign up here for exclusive offers and updates!</p>';
    
        //##############################
        //
        print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;
        //
        // display error messages before we print out the form

            if ($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print '<h2>Your  form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
                print '<ol>' . PHP_EOL;
                
                foreach ($errorMsg as $err) {
                    print '<li>' . $err . '</li>' . PHP_EOL;
                }
                
                print '</ol>' . PHP_EOL;
                print '</div>' . PHP_EOL;
            } 
        
        //################################
        //
        print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
        //
        /* Display the HTML form. Note that the action is to this same page. $phpSelf si
         * defined in top.php
         * Note the line:
         * value="<?php print $email; ?>
         * this makes the form sticky by displaying either the initial default value
         * or the value they typed in
         * Note this line:
         * <?php if($emailERROR) print 'class="mistake"'; ?>
         * this prints out a css class so that we can highlight the background, etc. to
         * make it stand out that a mistake happened here.
         */
 ?>
        
        
        
<form action = "<?php print $phpSelf; ?>"
        id = "frmRegister"
        method = "post">
        
        <fieldset class ="contact">
            <legend>Contact Information</legend>
            <p>
                <label class="required" for="txtFirstName">First Name</label>
                <input autofocus
                       <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                       id="txtFirstName"
                       maxlength="45"
                       name="txtFirstName"
                       onfocus="this.select()"
                       placeholder="Enter your first name"
                       tabindex="100"
                       type="text"
                       value="<?php print $firstName; ?>"
                >
            </p>

            <p>
                <label class="required" for="txtLastName">Last Name</label>
                <input
                       <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                       id="txtLastName"
                       maxlength="45"
                       name="txtLastName"
                       onfocus="this.select()"
                       placeholder="Enter your last name"
                       tabindex="100"
                       type="text"
                       value="<?php print $lastName; ?>"
                >
            </p>
            
            <p>
                <label class ="required" for ="txtEmail">Email</label>
                    <input
                        <?php if ($emailERROR) print 'class="mistake"'; ?>
                        id ="txtEmail"
                        maxlength="45"
                        name="txtEmail"
                        onfocus="this.select()"
                        placeholder="Enter your email address"
                        tabindex="120"
                        type="text"
                        value="<?php print $email; ?>"
                    >
            </p>
        </fieldset> <!-- ends contact --> 
        
        <fieldset class="listbox <?php if ($birthdayError) print 'mistake'; ?>">
            
            <legend>Birthday</legend>
            <select id="lstMonth"
                    name="lstMonth"
                    tabindex="520" >
                <option <?php if ($month == "January") print " selected "; ?>
                    value="January">January</option>
                
                <option <?php if ($month == "February") print " selected "; ?>
                    value="February">February</option>
                
                <option <?php if ($month == "March") print " selected "; ?>
                    value="March">March</option>
                
                <option <?php if ($month == "April") print " selected "; ?>
                    value="April">April</option>
                
                <option <?php if ($month == "May") print " selected "; ?>
                    value="May">May</option>
                
                <option <?php if ($month == "June") print " selected "; ?>
                    value="June">June</option>
                
                <option <?php if ($month == "July") print " selected "; ?>
                    value="July">July</option>
                
                <option <?php if ($month == "August") print " selected "; ?>
                    value="August">August</option>
                
                <option <?php if ($month == "September") print " selected "; ?>
                    value="September">September</option>
                
                <option <?php if ($month == "October") print " selected "; ?>
                    value="October">October</option>
                
                <option <?php if ($month == "November") print " selected "; ?>
                    value="November">November</option>
                
                <option <?php if ($month == "December") print " selected "; ?>
                    value="December">December</option>
            </select>
            
            <select id="lstDay"
                    name="lstDay"
                    tabindex="520" >
                <?php
                $i = 1;
                while ($i < 32) {
                    print'<option '; if ($day == $i) echo " selected ";
                    echo "value=" . $i . ">" . $i . '</option>' . PHP_EOL;
                    $i = $i + 1;
                }
                ?>
                
            </select>
            
        </fieldset>
        
        <fieldset class="radio <?php if ($visitERROR) print 'mistake'; ?>">
            <legend>Have you visited us before?</legend>
            <p>
                <label class="radio-field"><input type="radio" id="radVisitRecent" name="radVisit" value="Yes, recently" tabindex="572"
                <?php if ($visit == 'Yes, recently') echo 'checked = "checked" '; ?>>
                    Yes, recently</label>
            </p>
            <p>
                <label class="radio-field"><input type="radio" id="radVisitNotRecent" name="radVisit" value="Yes, but not recently" tabindex="574"
                <?php if ($visit == 'Yes, but not recently') echo ' checked = "checked" '; ?>>
                    Yes, but not recently</label>
            </p>
            <p>
                <label class="radio-field"><input type="radio" id="radVisitNo" name="radVisit" value="No" tabindex="574"
                <?php if ($visit == "No") echo ' checked = "checked" '; ?>>
                    No</label>
            </p>
        </fieldset>
        
        <fieldset class="checkbox <?php if ($activityERROR) print ' mistake'; ?>">
            <legend>Check off if you have the following allergies:</legend>
            
            <p>
                <label class="check-field">
                    <input <?php if ($gluten) print " checked "; ?>
                        id="chkGluten"
                        name="chkGluten"
                        tabindex="420"
                        type="checkbox"
                        value="Gluten">Gluten</label>
            </p>
            
            <p>
                <label class="check-field">
                    <input <?php if ($seafood) print " checked "; ?>
                        id="chkSeafood"
                        name="chkSeafood"
                        tabindex="420"
                        type="checkbox"
                        value="Seafood">Seafood</label>
            </p>
            
            <p>
                <label class="check-field">
                    <input <?php if ($peanut) print "checked" ; ?>
                        id="chkPeanut"
                        name="chkPeanut"
                        tabindex="430"
                        type="checkbox"
                        value="Peanut">Peanuts/tree nuts</label>
            </p>
        </fieldset>
        
        <fieldset class="textarea">
                <p>
                    <label class="required" for="txtComments">Comments</label>
                    <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                        id="txtComments"
                        name="txtComments"
                        onfocus="this.select()"
                        tabindex="200"><?php print $comments;?></textarea>
                </p>
        </fieldset>
       

        <fieldset class="buttons">
            <legend></legend>
            <input class="button" id="btnSubmit" name ="btnSubmit" tabindex="900" type="submit" value="Register">
        </fieldset> <!-- ends buttons -->
</form>
<?php
} // ends body submit
?>
        
    </article>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
