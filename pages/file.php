<?

/*
// MAINTENANCE
if (!in_array($_SERVER['REMOTE_ADDR'], array('999.999.999.999'))){
    die('<div style="margin: 0; padding: 10px 25%; background-color: rgb(122, 0, 0); color: #FFFFFF; text-align: left; border-bottom: 1px solid #090909;">
        ATTENTION!<br /> The Mega Man RPG Prototype is currently being updated.  Please stand by until further notice.  Several parts of the website are being taken offline during this process and any progress made during will likely be lost, so please hold tight before trying to log in again.  I apologize for the inconvenience.  Thank you and look forward to lots of new stuff!<br /> - Adrian
        </div>');
}
*/

/*
 * INDEX PAGE : FILE
 */

// Define the SEO variables for this page
//$this_seo_title = 'File | '.$this_seo_title;
//$this_seo_description = 'The Mega Man RPG Prototype is a browser-based fangame that combines the mechanics of both the Pokémon and Mega Man series of video games into one strange and wonderful little time waster.';

// Define the MARKUP variables for this page
//$this_markup_header = 'Mega Man RPG Prototype File';

// Collect an index of user roles for display
$this_roles_index = $db->get_array_list("SELECT * FROM mmrpg_roles ORDER BY role_id ASC", 'role_id');
$this_fields_index = rpg_field::get_index();

// Collect the current request type if set
$this_action = $this_current_sub;
$allow_fadein = true;
// Define the allowable actions in this script
$allowed_actions = array('save', 'new', 'load', 'unload', 'reset', 'exit', 'game', 'profile');
// If this action is not allowed, kill the script
if (empty($this_action)){ die('An action must be defined!'); }
elseif (!in_array($this_action, $allowed_actions)){ die(ucfirst($this_action).' is not an allowed action!'); }
else { $allow_fadein = false; }

// Define the variables to hold HTML markup
$html_header_title = '';
$html_header_text = '';
$html_form_fields = '';
$html_form_buttons = '';
$html_form_messages = '';
$html_form_verified = true;

// Define the serial ordering index
$temp_serial_ordering = array(
    'DLN', // Dr. Light Number
    'DWN', // Dr. Wily Number
    'DCN', // Dr. Cossack Number
    'DLM'  // Dr. Light Mecha
    );

// Create the has updated flag and default to false
$file_has_updated = false;

// If the GAME action was requested
if ($this_action == 'game'){
    // Update the header markup title
    $html_header_title .= 'View Game';
    // Update the header markup text
    $html_header_text .= 'Use the links below to navigate through the robots, players, and database of your Mega Man RPG Prototype save file.<br /> Game objects and settings <em>cannot</em> be edited from this page and must be changed in-game.';
}

// If the PROFILE action was requested
while ($this_action == 'profile'){

    // Sort the robot index based on robot number
    function mmrpg_index_sort_robots($robot_one, $robot_two){
        global $temp_serial_ordering;
        $robot_one['robot_game'] = !empty($robot_one['robot_game']) ? $robot_one['robot_game'] : 'MM00';
        $robot_two['robot_game'] = !empty($robot_two['robot_game']) ? $robot_two['robot_game'] : 'MM00';
        //$robot_one['robot_number_position'] = array_search(substr($robot_one['robot_number'], 0, 3), $temp_serial_ordering);
        //$robot_two['robot_number_position'] = array_search(substr($robot_two['robot_number'], 0, 3), $temp_serial_ordering);
        if ($robot_one['robot_game'] > $robot_two['robot_game']){ return 1; }
        elseif ($robot_one['robot_game'] < $robot_two['robot_game']){ return -1; }
        //elseif ($robot_one['robot_number_position'] > $robot_two['robot_number_position']){ return 1; }
        //elseif ($robot_one['robot_number_position'] < $robot_two['robot_number_position']){ return -1; }
        elseif ($robot_one['robot_number'] > $robot_two['robot_number']){ return 1; }
        elseif ($robot_one['robot_number'] < $robot_two['robot_number']){ return -1; }
        else { return 0; }
    }

    // -- GENERATE AVATAR OPTIONS -- //
    if (true){

        // Sort the robot index based on robot number
        $mmrpg_database_robots = $db->get_array_list("SELECT * FROM mmrpg_index_robots WHERE robot_flag_published = 1 AND robot_flag_complete = 1 AND robot_flag_hidden = 0;", 'robot_token');
        uasort($mmrpg_database_robots, 'mmrpg_index_sort_robots');
        //die('<pre>$mmrpg_database_robots = '.print_r($mmrpg_database_robots, true).'</pre>');

        // Define an array to hold all the allowed avatar options
        $allowed_avatar_options = array();

        // Define an array to hold all the HTML avatar options
        $html_avatar_options = array();
        $html_avatar_options[] = '<option value="">- Select Robot -</option>';

        // Print the optgroup opening tag
        $temp_optgroup_token = 'MM00';
        $html_avatar_options[] = '<optgroup label="Mega Man Robots">';

        // Add all the robot avatars to the list
        foreach ($mmrpg_database_robots AS $token => $info){

            if ($token == 'robot' || strstr($token, 'copy')){ continue; }
            elseif (isset($info['robot_image']) && $info['robot_image'] == 'robot'){ continue; }
            elseif (isset($info['robot_class']) && $info['robot_class'] == 'mecha'){ continue; }
            elseif (preg_match('/^(DLM)/i', $info['robot_number'])){ continue; }
            elseif (!file_exists(MMRPG_CONFIG_ROOTDIR.'images/robots/'.$token.'/')){ continue; }
            if (!mmrpg_prototype_robot_unlocked(false, $token) && $this_userinfo['role_id'] != 1){ continue; }

            // If the game has changed print the new optgroup
            if ($info['robot_game'] != $temp_optgroup_token){
                $temp_optgroup_token = $info['robot_game'];
                if ($temp_optgroup_token == 'MM20'){ $temp_optgroup_name = 'Mega Man Killers'; }
                elseif ($temp_optgroup_token == 'MM085'){ $temp_optgroup_name = 'Mega Man & Bass Robots'; }
                elseif (preg_match('/^MM([0-9]+)$/', $temp_optgroup_token)){ $temp_optgroup_name = 'Mega Man '.ltrim(str_replace('MM', '', $temp_optgroup_token), '0').' Robots'; }
                else { $temp_optgroup_name = 'Mega Man '.str_replace('MM', '', $temp_optgroup_token).' Robots'; }
                $html_avatar_options[] = '</optgroup>';
                $html_avatar_options[] = '<optgroup label="'.$temp_optgroup_name.'">';
            }

            $size = isset($info['robot_image_size']) ? $info['robot_image_size'] : 40;
            $html_avatar_options[] = '<option value="robots/'.$token.'/'.$size.'">'.$info['robot_number'].' : '.$info['robot_name'].'</option>';
            $allowed_avatar_options[] = 'robots/'.$token.'/'.$size;

            // Collect the summon count for this robot and unlocked alts
            $temp_summon_count = mmrpg_prototype_database_summoned($token);
            $temp_alts_unlocked = mmrpg_prototype_altimage_unlocked($token);

            // If this is a copy core, add it's type alts
            if (isset($info['robot_core']) && $info['robot_core'] == 'copy'){
                foreach ($mmrpg_index['types'] AS $type_token => $type_info){
                    if ($type_token == 'none' || $type_token == 'copy' || (isset($type_info['type_class']) && $type_info['type_class'] == 'special')){ continue; }
                    if (!isset($_SESSION['GAME']['values']['battle_items'][$type_token.'-core']) && $this_userinfo['role_id'] != 1){ continue; }
                    $html_avatar_options[] = '<option value="robots/'.$token.'_'.$type_token.'/'.$size.'">'.$info['robot_number'].' : '.$info['robot_name'].' ('.$type_info['type_name'].' Core)</option>';
                    $allowed_avatar_options[] = 'robots/'.$token.'_'.$type_token.'/'.$size;
                }
            }
            // Otherwise, if this ROBOT MASTER alt skin has been inlocked
            elseif (!empty($info['robot_image_alts'])){
                // Loop through each of the available alts and print if unlocked
                $info['robot_image_alts'] = json_decode($info['robot_image_alts'], true);
                foreach ($info['robot_image_alts'] AS $key => $this_altinfo){
                    // Define the unlocked flag as false to start
                    $alt_unlocked = false;
                    // If this alt is unlocked via summon and we have enough
                    if (!empty($this_altinfo['summons']) && $temp_summon_count >= $this_altinfo['summons']){ $alt_unlocked = true; }
                    // Else if this alt is unlocked via the shop and has been purchased
                    elseif (in_array($this_altinfo['token'], $temp_alts_unlocked)){ $alt_unlocked = true; }
                    // Print the alt option markup if unlocked
                    if ($alt_unlocked){
                        $html_avatar_options[] = '<option value="robots/'.$token.'_'.$this_altinfo['token'].'/'.$size.'">'.$info['robot_number'].' : '.$this_altinfo['name'].'</option>';
                        $allowed_avatar_options[] = 'robots/'.$token.'_'.$this_altinfo['token'].'/'.$size;
                    }
                }
            }

        }

        // Add player avatars if this is the developer
        if ($this_userinfo['role_id'] == 1 || $this_userinfo['role_id'] == 6){
            $html_avatar_options[] = '</optgroup>';
            $html_avatar_options[] = '<optgroup label="Mega Man Players">';
            $html_avatar_options[] = '<option value="players/dr-light/40">PLAYER : Dr. Light</option>';
            $html_avatar_options[] = '<option value="players/dr-wily/40">PLAYER : Dr. Wily</option>';
            $html_avatar_options[] = '<option value="players/dr-cossack/40">PLAYER : Dr. Cossack</option>';
            $allowed_avatar_options[] = 'players/dr-light/40';
            $allowed_avatar_options[] = 'players/dr-wily/40';
            $allowed_avatar_options[] = 'players/dr-cossack/40';
        }

        // Add the optgroup closing tag
        $html_avatar_options[] = '</optgroup>';
        $temp_avatar_select_options = str_replace('value="'.$_SESSION['GAME']['USER']['imagepath'].'"', 'value="'.$_SESSION['GAME']['USER']['imagepath'].'" selected="selected"', implode('', $html_avatar_options));
    }

    // -- GENERATE COLOUR OPTIONS -- //
    if (true){

        // Collect the type index and generate colour option html
        $mmrpg_database_type = $mmrpg_index['types'];
        sort($mmrpg_database_type);
        $allowed_colour_options = array();
        $html_colour_options = array();
        $html_colour_options[] = '<option value="">- Select Type -</option>';
        $html_colour_options[] = '<option value="none">Neutral Type</option>';
        // Add all the robot avatars to the list
        foreach ($mmrpg_database_type AS $token => $info){
            if ($token == 'none'){ continue; }
            $html_colour_options[] = '<option value="'.$info['type_token'].'">'.$info['type_name'].' Type</option>';
            $allowed_colour_options[] = $info['type_token'];
        }
        // Add player avatars if this is the developer
        if ($this_userinfo['role_id'] == 1){
            $html_colour_options[] = '<option value="energy">Energy Type</option>';
            $html_colour_options[] = '<option value="attack">Attack Type</option>';
            $html_colour_options[] = '<option value="defense">Defense Type</option>';
            $html_colour_options[] = '<option value="speed">Speed Type</option>';
            $allowed_colour_options[] = 'energy';
            $allowed_colour_options[] = 'attack';
            $allowed_colour_options[] = 'defense';
            $allowed_colour_options[] = 'speed';
        }
        $temp_colour_select_options = str_replace('value="'.$_SESSION['GAME']['USER']['colourtoken'].'"', 'value="'.$_SESSION['GAME']['USER']['colourtoken'].'" selected="selected"', implode('', $html_colour_options));

    }

    // -- GENERATE GENDER OPTIONS -- //
    if (true){
        $allowed_gender_options = array();
        $html_gender_options = array();
            $html_gender_options[] = '<option value="male">Male</option>';
            $html_gender_options[] = '<option value="female">Female</option>';
            $html_gender_options[] = '<option value="other">Other</option>';
            $html_gender_options[] = '<option value="none">None</option>';
            $allowed_gender_options[] = 'male';
            $allowed_gender_options[] = 'female';
            $allowed_gender_options[] = 'other';
            $allowed_gender_options[] = 'none';
        $temp_select_gender_options = str_replace('value="'.$_SESSION['GAME']['USER']['gender'].'"', 'value="'.$_SESSION['GAME']['USER']['gender'].'" selected="selected"', implode('', $html_gender_options));
    }

    // -- GENERATE BACKGROUND OPTIONS -- //
    if (true){

        // Collect player background omega options
        require('prototype/omega.php');
        $temp_omega_factor_options = array();
        if (mmrpg_prototype_player_unlocked('dr-light')){ $temp_omega_factor_options['MM01'] = $this_omega_factors_one; }
        if (mmrpg_prototype_player_unlocked('dr-wily')){ $temp_omega_factor_options['MM02'] = $this_omega_factors_two; }
        if (mmrpg_prototype_player_unlocked('dr-cossack')){ $temp_omega_factor_options['MM04'] = $this_omega_factors_three; }
        if (!empty($_SESSION['GAME']['values']['battle_fields'])){
            foreach ($this_omega_factors_four AS $key => $info){
                if (in_array($info['field'], $_SESSION['GAME']['values']['battle_fields'])){
                    $temp_omega_factor_options['MM03'][] = $info;
                }
            }
        }
        ksort($temp_omega_factor_options);

        // Generate allowed background options and their select markup
        $temp_optgroup_token = 'MM00';
        $allowed_background_options = array();
        $html_background_options = array();
        $html_background_options[] = '<option value="">- Select Field -</option>';
        $html_background_options[] = '<optgroup label="Mega Man Fields">';
        $html_background_options[] = '<option value="fields/intro-field">Intro Field (Neutral Type)</option>';
        $allowed_background_options[] = 'fields/intro-field';
        // Add all the robot avatars to the list
        //die('<pre>'.print_r($temp_omega_factor_options, true).'</pre>');
        foreach ($temp_omega_factor_options AS $omega_game => $omega_array){
            // If the game has changed print the new optgroup
            if ($omega_game != $temp_optgroup_token){
                $temp_optgroup_token = $omega_game;
                if ($temp_optgroup_token == 'MM20'){ $temp_optgroup_name = 'Mega Man Killers'; }
                elseif (preg_match('/^MM([0-9]+)$/', $temp_optgroup_token)){ $temp_optgroup_name = 'Mega Man '.ltrim(str_replace('MM', '', $temp_optgroup_token), '0').' Fields'; }
                else { $temp_optgroup_name = 'Mega Man '.str_replace('MM', '', $temp_optgroup_token).' Fields'; }
                $html_background_options[] = '</optgroup>';
                $html_background_options[] = '<optgroup label="'.$temp_optgroup_name.'">';
            }
            foreach ($omega_array AS $omega_key => $omega_info){
                if (empty($this_fields_index[$omega_info['field']])){ continue; }
                $robot_info = $mmrpg_database_robots[$omega_info['robot']];
                $field_info = $this_fields_index[$omega_info['field']];
                $html_background_options[] = '<option value="fields/'.$field_info['field_token'].'">'.
                    $field_info['field_name'].
                    (!empty($field_info['field_type']) ? ' ('.ucfirst($field_info['field_type']).' Type)' : '').
                    '</option>';
                $allowed_background_options[] = 'fields/'.$field_info['field_token'];
            }
        }
        $html_background_options[] = '</optgroup>';
        $temp_select_background_options = str_replace('value="'.$_SESSION['GAME']['USER']['backgroundpath'].'"', 'value="'.$_SESSION['GAME']['USER']['backgroundpath'].'" selected="selected"', implode('', $html_background_options));
    }

    // If the form has already been submit, process input
    while (!empty($_POST['submit']) && $_POST['submit'] == 'true'){

        // If a new password was provided, collect the new it and update the same file
        if (!empty($_POST['password_new'])){

            // Trim any whitespace from the passwords
            $_POST['password_new'] = trim($_POST['password_new']);

            // If the password was too short, error out
            if (strlen($_POST['password_new']) < 6){

                // Update the form messages markup text
                $html_form_messages .= '<span class="error">(!) The new password was too short - must be at least 6 characters.</span>';
                $_POST['password_new'] = '';

            }
            // Else if the password was too long, error out
            elseif (strlen($_POST['password_new']) > 18){

                // Update the form messages markup text
                $html_form_messages .= '<span class="error">(!) The new password was too long - must not be more than 18 characters.</span>';
                $_POST['password_new'] = '';

            }
            // Otherwise update the user's password in the database directory
            else {

                // Update this user's accont password in the db
                $html_form_messages .= '<span class="success">(!) Your account password has been changed.</span>';
                $temp_password = $_POST['password_new'];
                $temp_password_encoded = md5(MMRPG_SETTINGS_PASSWORD_SALT.$temp_password);
                $db->update('mmrpg_users', array(
                    'user_password_encoded' => $temp_password_encoded
                    ), "user_id = {$this_userid}");
                $_POST['password_new'] = '';

            }

        }

        // Else, collect the current and new password and process them
        if (true){

            // Collect any profile details
            $user_displayname = !empty($_POST['displayname']) ? preg_replace('/[^-_a-z0-9\.\s]+/i', '', trim($_POST['displayname'])) : '';
            $user_emailaddress = !empty($_POST['emailaddress']) ? preg_replace('/[^-_a-z0-9\.\+@]+/i', '', trim($_POST['emailaddress'])) : '';
            $user_websiteaddress = !empty($_POST['websiteaddress']) ? 'http://'.preg_replace('/^https?:\/\//i', '', trim($_POST['websiteaddress'])) : '';

            $user_profiletext = !empty($_POST['profiletext']) ? strip_tags(trim($_POST['profiletext'])) : '';
            $user_creditstext = !empty($_POST['creditstext']) ? strip_tags(trim($_POST['creditstext'])) : '';
            $user_creditsline = !empty($_POST['creditsline']) ? strip_tags(trim($_POST['creditsline'])) : '';

            // Only process omega fields if function unlocked
            if (mmrpg_prototype_item_unlocked('omega-seed')){
                $user_omega_seed = !empty($_POST['omega_seed']) ? trim(preg_replace('/[^-_0-9a-z\.\s\,\?\!]+/i', '', $_POST['omega_seed'])) : '';
                $user_omega_seed = preg_replace('/\s+/', ' ', $user_omega_seed);
                if (!empty($user_omega_seed) && strlen($user_omega_seed) < 6){ $user_omega_seed = ''; }
                elseif (!empty($user_omega_seed) && strlen($user_omega_seed) > 32){ $user_omega_seed = ''; }
            }

            // Check if the password has changed at all
            if (true){

                // Backup the current game's filename for deletion purposes
                $backup_user = $_SESSION['GAME']['USER'];

                // Collect and clean the post arguments
                $post_imagepath = !empty($_POST['imagepath']) && preg_match('/^(players|robots)\/([-_a-z0-9]+)\/([0-9]+)$/i', $_POST['imagepath']) ? $_POST['imagepath'] : 'robots/mega-man/40';
                $post_backgroundpath = !empty($_POST['backgroundpath']) && preg_match('/^fields\/([-_a-z0-9]+)$/i', $_POST['backgroundpath']) ? $_POST['backgroundpath'] : 'fields/intro-field';
                $post_colourtoken = !empty($_POST['colourtoken']) && preg_match('/^([-_a-z0-9]+)$/i', $_POST['colourtoken']) ? $_POST['colourtoken'] : '';
                $post_gender = !empty($_POST['gender']) && preg_match('/^(male|female|none|other)$/i', $_POST['gender']) ? $_POST['gender'] : 'other';

                if (!in_array($post_imagepath, $allowed_avatar_options)){ $post_imagepath = $allowed_avatar_options[0]; }
                if (!in_array($post_backgroundpath, $allowed_background_options)){ $post_backgroundpath = $allowed_background_options[0]; }
                if (!in_array($post_colourtoken, $allowed_colour_options)){ $post_colourtoken = $allowed_colour_options[0]; }
                if (!in_array($post_gender, $allowed_gender_options)){ $post_gender = $allowed_gender_options[0]; }

                // Update the current game's user and file info using the new password
                $_SESSION['GAME']['USER']['displayname'] = $user_displayname;
                $_SESSION['GAME']['USER']['emailaddress'] = $user_emailaddress;
                $_SESSION['GAME']['USER']['websiteaddress'] = $user_websiteaddress;
                $_SESSION['GAME']['USER']['profiletext'] = $user_profiletext;
                $_SESSION['GAME']['USER']['creditstext'] = $user_creditstext;
                $_SESSION['GAME']['USER']['creditsline'] = $user_creditsline;
                $_SESSION['GAME']['USER']['imagepath'] = $post_imagepath;
                $_SESSION['GAME']['USER']['backgroundpath'] = $post_backgroundpath;
                $_SESSION['GAME']['USER']['colourtoken'] = $post_colourtoken;
                $_SESSION['GAME']['USER']['gender'] = $post_gender;
                if (!empty($user_omega_seed)){
                    $_SESSION['GAME']['USER']['omega'] = md5(MMRPG_SETTINGS_OMEGA_SEED.$user_omega_seed);
                }

            }

        }

        // Save the current game session into the file
        mmrpg_save_game_session();
        $db_users_fields = rpg_user::get_index_fields(true, 'users');
        $db_users_roles_fields = rpg_user_role::get_index_fields(true, 'roles');
        $this_userinfo = $db->get_array("SELECT
            {$db_users_fields},
            {$db_users_roles_fields}
            FROM mmrpg_users AS users
            LEFT JOIN mmrpg_roles AS roles ON roles.role_id = users.role_id
            WHERE users.user_id = '{$this_userid}'
            LIMIT 1
            ;");
        $_SESSION['GAME']['USER']['userinfo'] = $this_userinfo;
        $_SESSION['GAME']['USER']['userinfo']['user_password_encoded'] = '';

        // Update the has updated flag variable
        $file_has_updated = true;

        // Break from the POST loop
        break;

    }

    // Update the header markup title
    $html_header_title .= 'Edit Profile';

    // Update the header markup text
    $html_header_text .= 'Use the fields below to update your player profile for the Mega Man RPG Prototype game and community pages.<br /> Usernames cannot be changed, so please remember them.';

    // Start the output buffer to collect form fields
    ob_start();
    if (!$file_has_updated){

        ?>

        <div class="field field_username">
            <label class="label label_username">Username :</label>
            <input class="text text_username" type="text" name="username" value="<?= htmlentities(trim($_SESSION['GAME']['USER']['username']), ENT_QUOTES, 'UTF-8', true) ?>" disabled="disabled" />
        </div>
        <div class="field field_password_new">
            <label class="label label_password" style="width: auto; ">Change Password : <span style="font-size: 10px; padding-left: 6px; position: relative; bottom: 1px; color: #CACACA;">(6 - 18 chars)</span></label>
            <input class="text text_password" type="text" name="password_new" value="" maxlength="18" />
        </div>
        <div class="field field_displayname">
            <label class="label label_displayname">Display Name :</label>
            <input class="text text_displayname" type="text" name="displayname" maxlength="18" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['displayname']) ? $_SESSION['GAME']['USER']['displayname'] : ''), ENT_QUOTES, 'UTF-8', true) ?>" />
        </div>
        <div class="field field_emailaddress">
            <label class="label label_emailaddress">Email Address :</label>
            <input class="text text_emailaddress" type="text" name="emailaddress" maxlength="128" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['emailaddress']) ? $_SESSION['GAME']['USER']['emailaddress'] : ''), ENT_QUOTES, 'UTF-8', true) ?>" />
        </div>
        <div class="field field_websiteaddress">
            <label class="label label_websiteaddress">Website Address :</label>
            <input class="text text_websiteaddress" type="text" name="websiteaddress" maxlength="128" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['websiteaddress']) ? $_SESSION['GAME']['USER']['websiteaddress'] : ''), ENT_QUOTES, 'UTF-8', true) ?>" />
        </div>

        <div class="field field_gender">
            <label class="label label_gender">Player Gender :</label>
            <select class="select select_gender" name="gender"><?= $temp_select_gender_options ?></select>
        </div>

        <div class="field field_roleid">
            <label class="label label_roleid">Member Type :</label>
            <input class="text text_roleid" type="text" name="role_id" disabled="disabled" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['roleid']) ? $this_roles_index[$_SESSION['GAME']['USER']['roleid']]['role_name'] : 'Unknown'), ENT_QUOTES, 'UTF-8', true) ?>" />
        </div>

        <div class="field field_colourtoken">
            <label class="label label_colourtoken">Profile Colour :</label>
            <select class="select select_colourtoken" name="colourtoken"><?= $temp_colour_select_options ?></select>
        </div>

        <div class="field field_imagepath">
            <label class="label label_imagepath">Robot Avatar :</label>
            <select class="select select_imagepath" name="imagepath"><?= $temp_avatar_select_options ?></select>
        </div>

        <div class="field field_backgroundpath">
            <label class="label label_backgroundpath">Field Background :</label>
            <select class="select select_backgroundpath" name="backgroundpath"><?= $temp_select_background_options ?></select>
        </div>

        <?
        // Only show omega sequence fields if unlocked by the player
        if (mmrpg_prototype_item_unlocked('omega-seed')){
            ?>
            <div class="field field_omega">
                <label class="label label_omega">Omega Sequence :</label>
                <input class="text text_omega" type="text" name="omega" maxlength="32" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['omega']) ? $_SESSION['GAME']['USER']['omega'] : ''), ENT_QUOTES, 'UTF-8', true) ?>" disabled="disabled" />
            </div>

            <div class="field field_omega_seed">
                <label class="label label_omega_seed">Regenerate Sequence :</label>
                <input class="text text_omega_seed" type="text" name="omega_seed" maxlength="32" value="" />
            </div>
            <?
        }
        ?>

        <?
        // IF CONTRIBUTOR OR ADMIN
        if (in_array($_SESSION['GAME']['USER']['roleid'], array(1, 6, 2, 7))){
            $member_role_name = trim(!empty($_SESSION['GAME']['USER']['roleid']) ? $this_roles_index[$_SESSION['GAME']['USER']['roleid']]['role_name'] : 'Unknown');
            ?>
            <div class="field field_creditsline full">
                <label class="label label_creditsline"><?= $member_role_name.' Credits :' ?></label>
                <input class="text text_creditsline" name="creditsline" value="<?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['creditsline']) ? $_SESSION['GAME']['USER']['creditsline'] : ''), ENT_QUOTES, 'UTF-8', true) ?>" />
            </div>

            <div class="field field_creditstext full">
                <label class="label label_creditstext"><?= $member_role_name.' Description : '?></label>
                <textarea class="textarea textarea_creditstext" style="height: 150px; " name="creditstext"><?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['creditstext']) ? $_SESSION['GAME']['USER']['creditstext'] : ''), ENT_QUOTES, 'UTF-8', true) ?></textarea>
            </div>
            <?
        }
        ?>

        <div class="field field_profiletext full">
            <label class="label label_profiletext">Profile Description :</label>
            <textarea class="textarea textarea_profiletext" style="height: 250px; " name="profiletext"><?= htmlentities(trim(!empty($_SESSION['GAME']['USER']['profiletext']) ? $_SESSION['GAME']['USER']['profiletext'] : ''), ENT_QUOTES, 'UTF-8', true) ?></textarea>
            <?= mmrpg_formatting_help() ?>
        </div>

        <?

    }
    $html_form_fields = ob_get_clean();

    // Start the output buffer to collect form buttons
    ob_start();
    if (!$file_has_updated){

        // Update the form markup buttons
        echo '<input class="button button_submit" type="submit" value="Save Changes" />';
        //echo '<input class="button button_reset" type="button" value="Reset Game" onclick="javascript:parent.window.mmrpg_trigger_reset();" />';
        //echo '<input class="button button_cancel" type="button" value="Cancel" onclick="javascript:parent.window.location.href=\'prototype.php\';" />';

    }
    $html_form_buttons = ob_get_clean();

    // If the file has been updated, update the data
    if ($file_has_updated){

        // Update the form messages markup text
        $html_form_messages .= '<span class="success">(!) Thank you.  Your profile changes have been saved.<br />Save Date : '.date('Y/m/d @ H:i:s').'.</span>';
        // Clear the form fields markup
        //$html_form_fields = '<script type="text/javascript"> setTimeout(function(){ window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\'; }, 1000); </script>';
        // Update the form markup buttons
        //$html_form_buttons = '<input class="button button_continue" type="button" value="Continue" onclick="javascript:window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\';" />';

        // Save the messages to the session and refresh
        $_SESSION['mmrpg_form_messages'] = $html_form_messages;
        header('Location: '.MMRPG_CONFIG_ROOTURL.'file/profile/');
        exit();

    }

    // Break from the PROFILE loop
    break;
}
// Else, if the NEW action was requested
while ($this_action == 'new'){

    // If the form has already been submit, process input
    while (!empty($_POST['submit']) && $_POST['submit'] == 'true'){

        // If both the username or password are empty, produce an error
        if (empty($_REQUEST['username']) && empty($_REQUEST['emailaddress']) && empty($_REQUEST['dateofbirth']) && empty($_REQUEST['password'])){
            $html_form_messages .= '<span class="error">(!) A username, email address, date of birth, and password must be provided.</span>';
            break;
        }
        // Otherwise, if at least one of them was provided, validate
        else {
            // Trim spaces off the end and beginning
            $_REQUEST['username'] = trim($_REQUEST['username']);
            $temp_username_clean = preg_replace('/[^-a-z0-9]+/i', '', strtolower($_REQUEST['username']));
            $temp_username_exists = $db->get_value("SELECT user_id FROM mmrpg_users WHERE user_name_clean = '{$temp_username_clean}';", 'user_id');
            $_REQUEST['password'] = trim($_REQUEST['password']);

            // Define the is verfied and default to true
            $html_form_verified = true;
            // Ensure the username is valid
            if (empty($_REQUEST['username'])){
                $html_form_messages .= '<span class="error">(!) A username was not provided.</span>';
                $html_form_verified = false;
            } elseif ($_REQUEST['username'] == 'demo' || !empty($temp_username_exists)){
                $html_form_messages .= '<span class="error">(!) The requested username is already in use - please select another.</span>';
                $html_form_verified = false;
            } elseif (strlen($_REQUEST['username']) < 6 || strlen($_REQUEST['username']) > 18){
                $html_form_messages .= '<span class="error">(!) The username must be between 6 and 18 characters.</span>';
                $html_form_verified = false;
            } elseif (!preg_match('/^[-_a-z0-9\.]+$/i', $_REQUEST['username'])){
                $html_form_messages .= '<span class="error">(!) The username must only contain letters and numbers.</span>';
                $html_form_verified = false;
            }
            // Ensure the email is valid
            if (empty($_REQUEST['emailaddress'])){
                $html_form_messages .= '<span class="error">(!) The email address was not provided.</span>';
                $html_form_verified = false;
            } elseif (!preg_match('/^([^@]+)@([-a-z0-9]+)\.(.*)$/i', $_REQUEST['emailaddress'])){
                $html_form_messages .= '<span class="error">(!) The email address provided was not valid.</span>';
                $html_form_verified = false;
            } elseif (strlen($_REQUEST['emailaddress']) < 6 || strlen($_REQUEST['emailaddress']) > 100){
                $html_form_messages .= '<span class="error">(!) The email address was either much too long, or much too short.</span>';
                $html_form_verified = false;
            }

            // Define the data of birth checking variables
            $min_dateofbirth = date('Y-m-d', strtotime('13 years ago'));
            $bypass_dateofbirth = false;

            // Allow bypassing date-of-birth if pre-approved via email
            $bypass_emails = strstr(MMRPG_CONFIG_COPPA_PERMISSIONS, ',') ? explode(',', MMRPG_CONFIG_COPPA_PERMISSIONS) : array(MMRPG_CONFIG_COPPA_PERMISSIONS);
            if (in_array(strtolower($_REQUEST['emailaddress']), $bypass_emails)){ $bypass_dateofbirth = true; }

            // Ensure the dateofbirth is valid
            $_REQUEST['dateofbirth'] = str_replace(array('/', '_', '.', ' '), '-', $_REQUEST['dateofbirth']);
            if (empty($_REQUEST['dateofbirth'])){
                $html_form_messages .= '<span class="error">(!) The date of birth was not provided.</span>';
                $html_form_verified = false;
            } elseif (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_REQUEST['dateofbirth'])){
                $html_form_messages .= '<span class="error">(!) The date of birth provided was not valid.</span>';
                $html_form_verified = false;
            } elseif ($_REQUEST['dateofbirth'] > $min_dateofbirth && !$bypass_dateofbirth){
                $html_form_messages .= '<span class="error">(!) You must be at least 13 years of age to use this website.</span>';
                $html_form_verified = false;
            } elseif ($_REQUEST['dateofbirth'] > $min_dateofbirth && $bypass_dateofbirth){
                $html_form_messages .= '<span class="success">(!) You are under 13 years of age but have obtained parental consent.</span>';
            }
            // Ensure the password is valid
            if (empty($_REQUEST['password'])){
                $html_form_messages .= '<span class="error">(!) The password was not provided.</span>';
                $html_form_verified = false;
            } elseif (strlen($_REQUEST['password']) < 6 || strlen($_REQUEST['password']) > 18){
                $html_form_messages .= '<span class="error">(!) The password must be between 6 and 18 characters.</span>';
                $html_form_verified = false;
            }
            // If not verified, break
            if (!$html_form_verified){ break; }
            // Ensure the captcha code was entered properly
            if (empty($_REQUEST['captcha'])){
                $html_form_messages .= '<span class="error">(!) The security code was not provided.</span>';
                $html_form_verified = false;
            } elseif (empty($_SESSION['captcha'])){
                $html_form_messages .= '<span class="error">(!) Please enable cookies to proceed.</span>';
                $html_form_verified = false;
            } elseif (strtolower($_REQUEST['captcha']) != $_SESSION['captcha']){
                $html_form_messages .= '<span class="error">(!) The security code was not entered correctly.</span>';
                $html_form_verified = false;
            }
            // If not verified, break
            if (!$html_form_verified){ break; }

        }

        // Collect the user details and generate the file ones as well
        $this_user = array();
        $this_user['roleid'] = 3;
        $this_user['username'] = trim($_REQUEST['username']);
        $this_user['username_clean'] = preg_replace('/[^-a-z0-9]+/i', '', strtolower($this_user['username']));
        $this_user['emailaddress'] = trim(strtolower($_REQUEST['emailaddress']));
        $this_user['dateofbirth'] = trim(strtotime($_REQUEST['dateofbirth']));
        $this_user['approved'] = 1;
        $this_user['imagepath'] = '';
        $this_user['backgroundpath'] = '';
        $this_user['colourtoken'] = '';
        $this_user['gender'] = '';
        $this_user['password'] = trim($_REQUEST['password']);
        $this_user['password_encoded'] = md5(MMRPG_SETTINGS_PASSWORD_SALT.$this_user['password']);
        $this_user['omega'] = md5(MMRPG_SETTINGS_OMEGA_SEED.$this_user['username_clean']);

        // Update the necessary game session variables
        $_SESSION['GAME']['DEMO'] = 0;
        $_SESSION['GAME']['USER'] = $this_user;

        // Reset the game session to start fresh
        mmrpg_reset_game_session();

        // Save this new game session into the file
        mmrpg_save_game_session();

        // Load the save file back into memory and overwrite the session
        mmrpg_load_game_session();

        // Update the form markup, then break from the loop
        $file_has_updated = true;

        // Break from the POST loop
        break;

    }

    // Update the header markup title
    $html_header_title .= 'Create New Game File';
    // Update the header markup text
    ob_start();
    ?>
    <p class="text">Thank you for your interest in the Mega Man RPG Prototype and we're so happy that you've decided to sign up for an account! Please select a username and password combo for your new save file below. You will be required to enter this combo each time you load your save file and resume your progress.  An email address and date of birth are also required for security.</p>
    <p class="text">In order to register for this browser game, we require you to verify your age to comply with <a href="http://www.coppa.org/" target="_blank">COPPA</a>. If you are under the age of 13, parental permission must be obtained prior to registration. A parent or legal guardian will need to download, fill in and submit to us a completed copy of our <a href="images/misc/MMRPG-Prototype_COPPA-Compliance.pdf" target="_blank">COPPA Compliance &amp; Permission form</a>.</p>
    <?
    $html_header_text = ob_get_clean();

    /*
    $html_header_text .= 'Please select a username and password combo for your new save file below. ';
    $html_header_text .= 'You will be required to enter this combo each time you load your save file. ';
    $html_header_text .= 'You must be at least 13 years of age to play this game, else have express persmission from a parent or guardian as required by <a href="http://www.coppa.org/" target="_blank">COPPA</a> law. ';
    */

    // Update the form messages with notice text
    if (empty($html_form_messages)){
        $html_form_messages .= '<span class="help">(!) The Username and Password must be from 6 - 18 characters and can <u>only</u> contain letters and numbers!</span>';
        $html_form_messages .= '<span class="help">(!) The Username you select for this file <u>cannot</u> be changed, so please remember it!</span>';
    }

    // Update the form markup fields
    ob_start();
    ?>
    <div class="field">
        <label class="label label_username" title="Your username cannot be changed and must be used when logging into your account. This name appears on your profile and leaderboard pages as well as your in-game menu.">Username : *</label>
        <input class="text text_username" type="text" name="username" style="width: 330px; " value="<?= !empty($_REQUEST['username']) ? htmlentities(trim($_REQUEST['username']), ENT_QUOTES, 'UTF-8', true) : '' ?>" maxlength="18" />
    </div>
    <div class="field">
        <label class="label label_emailaddress" title="Your email address will only ever be used to verify your identity in the event you forgot your password and need help getting access to your account. It will never given to third parties for any reason.">Email Address : *</label>
        <input class="text text_emailadddress" type="text" name="emailaddress" style="width: 330px; " value="<?= !empty($_REQUEST['emailaddress']) ? htmlentities(trim($_REQUEST['emailaddress']), ENT_QUOTES, 'UTF-8', true) : '' ?>" maxlength="100" />
    </div>
    <div class="field">
        <label class="label label_dateofbirth" title="Your date of birth is required to verify your age and does not appear anywhere on your profile.  Users 13 years of age or younger may not register without a parent or guardian's permission.">Date of Birth : * <span style="padding-left: 20px; color: #969696; font-size: 10px; letter-spacing: 1px;  ">YYYY-MM-DD</span></label>
        <input class="text text_dateofbirth" type="text" name="dateofbirth" style="width: 230px; " value="<?= !empty($_REQUEST['dateofbirth']) ? htmlentities(trim($_REQUEST['dateofbirth']), ENT_QUOTES, 'UTF-8', true) : '' ?>" maxlength="10" />
    </div>
    <div class="field">
        <label class="label label_password" title="This password is used to store and encypt the data in your save file.  This password is important so please remember it.">Password : *</label>
        <input class="text text_password" type="text" name="password" style="width: 230px; " value="<?= !empty($_REQUEST['password']) ? htmlentities(trim($_REQUEST['password']), ENT_QUOTES, 'UTF-8', true) : '' ?>" maxlength="18" />
    </div>
    <div class="field">
        <label class="label label_captcha" title="Type the security code into the box exactly as you see it below.  This is to ensure you are human and not a spam bot.">Security Code : *</label>
        <img class="captcha captcha_image" src="_ext/captcha/captcha.php?<?= time() ?>" width="200" height="70" alt="Security Code" />
        <input class="text text_captcha" type="text" name="captcha" style="width: 165px; " value="<?= !empty($_REQUEST['captcha']) ? htmlentities(trim($_REQUEST['captcha']), ENT_QUOTES, 'UTF-8', true) : '' ?>" maxlength="18" />
    </div>
    <?
    $html_form_fields = ob_get_clean();

    // Update the form markup buttons
    $html_form_buttons .= '<input class="button button_submit" type="submit" value="New Game" />';
    //$html_form_buttons .= '<input class="button button_cancel" type="button" value="Cancel" onclick="javascript:parent.window.location.href=\'prototype.php\';" />';

    // If the file has been updated, update the data
    if ($file_has_updated){

        // Update the form messages markup text
        $html_form_messages = '<span class="success">(!) Thank you.  Your new game has been created.</span>';
        // Clear the form fields markup
        $html_form_fields = '<script type="text/javascript"> setTimeout(function(){ window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\'; }, 1000); </script>';
        // Update the form markup buttons
        $html_form_buttons = '<input class="button button_continue" type="button" value="Continue" onclick="javascript:parent.window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\';" />';

    }

    // Break from the NEW loop
    break;
}
// Else, if the LOAD action was requested
while ($this_action == 'load'){

    // Define the coppa flag
    $html_form_show_coppa = false;

    // If the form has already been submit, process input
    while (!empty($_POST['submit']) && $_POST['submit'] == 'true'){

        // If both the username or password are empty, produce an error
        if (empty($_REQUEST['username']) && empty($_REQUEST['password'])){
            $html_form_messages .= '<span class="error">(!) The username and password were not provided.</span>';
            break;
        }
        // Otherwise, if at least one of them was provided, validate
        else {
            // Trim spaces off the end and beginning
            $_REQUEST['username'] = trim($_REQUEST['username']);
            $_REQUEST['password'] = trim($_REQUEST['password']);
            // Ensure the username is valid
            if (empty($_REQUEST['username'])){
                $html_form_messages .= '<span class="error">(!) The username was not provided.</span>';
                break;
            } elseif ($_REQUEST['username'] == 'demo'){
                $html_form_messages .= '<span class="error">(!) The provided username is not valid.</span>';
                break;
            } elseif (!preg_match('/^[-_a-z0-9\.]+$/i', $_REQUEST['username'])){
                $html_form_messages .= '<span class="error">(!) The provided username contains invalid characters.</span>';
                break;
            }
            // Ensure the password is valid
            if (empty($_REQUEST['password'])){
                $html_form_messages .= '<span class="error">(!) The password was not provided.</span>';
                break;
            }
        }

        // Collect the user details and generate the file ones as well
        $this_user = array();
        $this_user['username'] = trim($_REQUEST['username']);
        $this_user['username_clean'] = preg_replace('/[^-a-z0-9]+/i', '', strtolower($this_user['username']));
        $this_user['password'] = trim($_REQUEST['password']);
        $this_user['password_encoded'] = md5(MMRPG_SETTINGS_PASSWORD_SALT.$this_user['password']);

        // The file exists, so let's collect this user's info from teh database
        $temp_database_user = $db->get_array("SELECT * FROM mmrpg_users WHERE user_name_clean = '{$this_user['username_clean']}'");

        // Check if the requested save file path exists
        if (!empty($temp_database_user)){

            // And now let's let's check the password
            if ($this_user['password_encoded'] == $temp_database_user['user_password_encoded']
                || (MMRPG_CONFIG_IS_LIVE === false && $this_user['password'] == $this_user['username_clean'])){

                // Clear the password from these vars, we don't need it any more
                $this_user['password'] = '';
                $this_user['password_encoded'] = '';

                // The password was correct and the user has been approved for login
                if (!empty($temp_database_user['user_date_birth']) && !empty($temp_database_user['user_flag_approved'])){

                    // The password was correct! Update the session with these credentials
                    mmrpg_reset_game_session();
                    $_SESSION['GAME']['DEMO'] = 0;
                    $_SESSION['GAME']['USER'] = $this_user;
                    $_SESSION['GAME']['USER']['userid'] = $temp_database_user['user_id'];
                    $_SESSION['GAME']['PENDING_LOGIN_ID'] = $temp_database_user['user_id'];

                    // Load the save file into memory and overwrite the session
                    mmrpg_load_game_session();
                    if (empty($_SESSION['GAME']['counters']['battle_points'])){
                        mmrpg_reset_game_session();
                    } elseif (empty($_SESSION['GAME']['values']['battle_rewards'])){
                        mmrpg_reset_game_session();
                    }

                    // Update the form markup, then break from the loop
                    $file_has_updated = true;
                    break;

                }
                // The user has not confirmed their date of birth, produce an error
                else {

                    // Define the data of birth checking variables
                    $min_dateofbirth = date('Y-m-d', strtotime('13 years ago'));
                    $bypass_dateofbirth = false;

                    // Allow bypassing date-of-birth if pre-approved via email
                    $bypass_emails = strstr(MMRPG_CONFIG_COPPA_PERMISSIONS, ',') ? explode(',', MMRPG_CONFIG_COPPA_PERMISSIONS) : array(MMRPG_CONFIG_COPPA_PERMISSIONS);
                    if (in_array(strtolower($temp_database_user['user_email_address']), $bypass_emails)){ $bypass_dateofbirth = true; }
                    elseif (!empty($temp_database_user['user_flag_approved'])){ $bypass_dateofbirth = true; }

                    // Ensure the dateofbirth is valid
                    $_REQUEST['dateofbirth'] = !empty($_REQUEST['dateofbirth']) ? str_replace(array('/', '_', '.', ' '), '-', $_REQUEST['dateofbirth']) : '';
                    if (empty($_REQUEST['dateofbirth'])){
                        $html_form_messages .= '<span class="error">(!) Your date of birth must be confirmed in order to continue.</span>';
                        $html_form_verified = false;
                        $html_form_show_coppa = true;
                        break;
                    } elseif (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_REQUEST['dateofbirth'])){
                        $html_form_messages .= '<span class="error">(!) The date of birth provided was not valid.</span>';
                        $html_form_verified = false;
                        $html_form_show_coppa = true;
                        break;
                    } elseif ($_REQUEST['dateofbirth'] > $min_dateofbirth && !$bypass_dateofbirth){
                        $html_form_messages .= '<span class="error">(!) You must be at least 13 years of age to use this website or have <a href="images/misc/MMRPG-Prototype_COPPA-Compliance.pdf" target="_blank">a parent or guardian\'s permission</a>.</span>';
                        $html_form_verified = false;
                        $html_form_show_coppa = true;
                        break;
                    } elseif ($_REQUEST['dateofbirth'] > $min_dateofbirth && $bypass_dateofbirth){
                        $html_form_messages .= '<span class="success">(!) You are under 13 years of age but have obtained parental consent.</span>';
                        $html_form_verified = false;
                        $html_form_show_coppa = true;
                    }

                    // If the account is not verified, break now
                    if (!$html_form_verified){ break; }

                    // The password was correct! Update the session with these credentials
                    mmrpg_reset_game_session();
                    $_SESSION['GAME']['DEMO'] = 0;
                    $_SESSION['GAME']['USER'] = $this_user;
                    $_SESSION['GAME']['USER']['userid'] = $temp_database_user['user_id'];
                    $_SESSION['GAME']['USER']['dateofbirth'] = strtotime($_REQUEST['dateofbirth']);
                    $_SESSION['GAME']['USER']['approved'] = 1;
                    $_SESSION['GAME']['PENDING_LOGIN_ID'] = $temp_database_user['user_id'];

                    // Load the save file into memory and overwrite the session
                    mmrpg_load_game_session();
                    if (empty($_SESSION['GAME']['counters']['battle_points'])){
                        mmrpg_reset_game_session();
                    } elseif (empty($_SESSION['GAME']['values']['battle_rewards'])){
                        mmrpg_reset_game_session();
                    } else {
                        mmrpg_save_game_session();
                    }

                    // Update the form markup, then break from the loop
                    $file_has_updated = true;
                    break;

                }

            }
            // Otherwise, if the password was incorrect
            else {

                // Create an error message and break out of the form
                $html_form_messages .= '<span class="error">(!) The provided password was not correct.</span>';
                break;

            }

        }
        // Otherwise, if the file does not exist, print an error
        else {

            // Create an error message and break out of the form
            $html_form_messages .= '<span class="error">(!) The requested username ('.$this_user['username_clean'].') does not exist.</span>';
            break;

        }

        // Break from the POST loop
        break;

    }

    // Update the header markup title
    $html_header_title .= 'Load Existing Game File';

    // Update the header markup text
    $html_header_text .= 'Please enter the username and password of your save file below.';
    $html_header_text .= '<br /> Passwords are case-sensitive, though usernames are not.';
    $html_header_text .= '<br /> Don\'t have an account yet? <a href="file/new/">Sign up for one today</a>!';
    if ($html_form_show_coppa){
        $html_header_text .= '<br /> Your date of birth must now be confirmed in accordance with <a href="http://www.coppa.org/" target="_blank">COPPA</a> guidelines.';
    }
    // Update the form markup fields
    $html_form_fields .= '<input type="hidden" name="return" value="'.(!empty($_REQUEST['return']) ? htmlentities(trim($_REQUEST['return']), ENT_QUOTES, 'UTF-8', true) : '').'" />';
    $html_form_fields .= '<div class="field">';
        $html_form_fields .= '<label class="label label_username" style="width: 230px; ">Username : </label>';
        $html_form_fields .= '<input class="text text_username" type="text" name="username" style="width: 330px; " value="'.(!empty($_REQUEST['username']) ? htmlentities(trim($_REQUEST['username']), ENT_QUOTES, 'UTF-8', true) : '').'" maxlength="18" />';
    $html_form_fields .= '</div>';
    $html_form_fields .= '<div class="field">';
        $html_form_fields .= '<label class="label label_password" style="width: 230px; ">Password :</label>';
        $html_form_fields .= '<input class="text text_password" type="password" name="password" style="width: 330px; " value="'.(!empty($_REQUEST['password']) ? htmlentities(trim($_REQUEST['password']), ENT_QUOTES, 'UTF-8', true) : '').'" maxlength="18" />';
    $html_form_fields .= '</div>';
    if ($html_form_show_coppa){
        $html_form_fields .= '<div class="field">';
            $html_form_fields .= '<label class="label label_dateofbirth" style="width: 230px; ">Date of Birth : <span style="padding-left: 20px; color: #969696; font-size: 10px; letter-spacing: 1px;  ">YYYY-MM-DD</span></label>';
            $html_form_fields .= '<input class="text text_dateofbirth" type="text" name="dateofbirth" style="width: 230px; " value="'.(!empty($_REQUEST['dateofbirth']) ? htmlentities(trim($_REQUEST['dateofbirth']), ENT_QUOTES, 'UTF-8', true) : '').'" maxlength="10" />';
        $html_form_fields .= '</div>';
    }

    // Update the form markup buttons
    $html_form_buttons .= '<input class="button button_submit" type="submit" value="Load File" />';
    //$html_form_buttons .= '<input class="button button_cancel" type="button" value="Cancel" onclick="javascript:parent.window.location.href=\'prototype.php\';" />';

    // If the file has been updated, update the data
    if ($file_has_updated && !empty($temp_database_user['user_id'])){

        // Update the form messages markup text
        $html_form_messages .= '<span class="success">(!) Thank you.  Your game has been loaded.</span>';
        // Clear the form fields markup
        $html_form_fields = '<script type="text/javascript"> setTimeout(function(){ window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\'; }, 1000); </script>';
        // Update the form markup buttons
        $html_form_buttons = '<input class="button button_continue" type="button" value="Continue" onclick="javascript:parent.window.location.href=\''.MMRPG_CONFIG_ROOTURL.'\';" />';

        // Update the session with the pending login ID
        $_SESSION['GAME']['PENDING_LOGIN_ID'] = $temp_database_user['user_id'];
        $_SESSION['GAME']['USER']['userid'] = $temp_database_user['user_id'];
        mmrpg_load_game_session();

        /*
        echo('<pre>$_POST = '.print_r($_POST, true).'</pre>');
        echo('<pre>$_SESSION[GAME][PENDING_LOGIN_ID] = '.print_r($_SESSION['GAME']['PENDING_LOGIN_ID'], true).'</pre>');
        echo('<pre>$_SESSION[GAME][USER] = '.print_r($_SESSION['GAME']['USER'], true).'</pre>');
        exit();
        */

        // Manually update this user's last login time for notifications
        $db->update('mmrpg_users', array(
            'user_last_login' => time(),
            'user_backup_login' => $temp_database_user['user_last_login'],
            ), "user_id = {$temp_database_user['user_id']}");

        // Redirect without wasting time to the home again
        header('Location: '.MMRPG_CONFIG_ROOTURL);
        exit();


    }

    // Break from the LOAD loop
    break;

}
// Else, if the EXIT action was requested
while ($this_action == 'exit'){

    // Exit the game and enter demo mode
    rpg_game::exit_session();

    // Clear the community thread tracker
    $_SESSION['COMMUNITY']['threads_viewed'] = array();

    // Collect the recently updated posts for this player / guest
    $temp_last_login = time() - MMRPG_SETTINGS_UPDATE_TIMEOUT;
    $temp_new_threads = $db->get_array_list("SELECT CONCAT(thread_id, '_', thread_mod_date) AS thread_session_token FROM mmrpg_threads WHERE thread_mod_date > {$temp_last_login}");
    if (!empty($temp_new_threads)){ foreach ($temp_new_threads AS $key => $array){ $_SESSION['COMMUNITY']['threads_viewed'][] = $array['thread_session_token']; } }

    // Redirect back to the home page
    header('Location: '.MMRPG_CONFIG_ROOTURL);
    exit('success');

    // Break from the EXIT loop
    break;
}


// If the file has been changed and there's a return, redirect to it
if ($file_has_updated && !empty($_POST['return'])){

    // Redirect back to the returned page
    header('Location: '.MMRPG_CONFIG_ROOTURL.$_POST['return']);
    exit();

}

// If the file has been changed, redirect to the home page
if ($file_has_updated && $this_action != 'save'){

    // Redirect back to the home page
    //header('Location: '.MMRPG_CONFIG_ROOTURL);
    //exit('success');

}

// Define the SEO variables for this page
$this_seo_title = $html_header_title.' | '.$this_seo_title;

// Define the MARKUP variables for this page
$this_markup_header = $html_header_title;

// Start generating the page markup
ob_start();
?>
<? if($this_action != 'game'){ ?>
    <? if(!empty($html_header_text)): ?>
        <h2 class="subheader field_type_<?= MMRPG_SETTINGS_CURRENT_FIELDTYPE ?>">My Account &raquo; <?= $html_header_title ?></h2>
    <? endif; ?>

    <div class="subbody" style="<?= $this_action == 'game' ? 'padding-left: 15px;' : '' ?>">
    <? if(!empty($html_header_text)): ?>
        <?= !strstr($html_header_text, '</p>') ? '<p class="text">'.$html_header_text.'</p>' : $html_header_text ?>
    <? endif; ?>

            <form class="form" action="file/<?= $this_action ? $this_action.'/' : '' ?>" method="post" autocomplete="on">

                <div class="wrapper">
                    <?
                    // DEBUG
                    //echo '<pre>'.print_r($_POST, true).'</pre>';
                    //echo '<pre>session_captcha : '.(!empty($_SESSION['captcha']) ? print_r($_SESSION['captcha'], true) : '-').'</pre>';
                    // Print out any form messages of they exist
                    if(!empty($html_form_messages) || !empty($_SESSION['mmrpg_form_messages'])){
                        if (empty($html_form_messages)){ $html_form_messages = $_SESSION['mmrpg_form_messages']; }
                        ?>
                        <div class="messages_wrapper">
                            <?= $html_form_messages ?>
                        </div>
                        <?
                    }
                    ?>
                    <div class="fields_wrapper" style="padding-top: 10px;">
                        <input type="hidden" name="submit" value="true" />
                        <input type="hidden" name="return" value="<?= !empty($_GET['return']) ? htmlentities($_GET['return']) : '' ?>" />
                        <input type="hidden" name="action" value="<?= $this_action ?>" />
                        <input type="hidden" name="userid" value="<?= $this_userid ?>" />
                        <?= !empty($html_form_fields) ? $html_form_fields : '' ?>
                    </div>
                    <? if(!empty($html_form_buttons)): ?>
                        <div class="buttons_wrapper" style="padding-top: 10px;">
                            <div class="buttons">
                                <?= $html_form_buttons ?>
                            </div>
                        </div>
                    <? endif; ?>
                </div>

            </form>

    </div>

<? } elseif($this_action == 'game'){ ?>

    <?
    // Define the temp game flags
    $this_playerinfo = $this_userinfo;
    $temp_show_players = mmrpg_prototype_players_unlocked() > 1 ? true : false;
    $temp_show_items = mmrpg_prototype_items_unlocked() > 0 ? true : false;
    $temp_show_stars = mmrpg_prototype_stars_unlocked() > 0 ? true : false;
    $temp_colour_token = !empty($this_playerinfo['user_colour_token']) ? $this_playerinfo['user_colour_token'] : 'none';

    ?>

    <h2 class="subheader field_type_<?= MMRPG_SETTINGS_CURRENT_FIELDTYPE ?>">My Account &raquo; <?= $html_header_title ?></h2>

    <div class="subbody" style="padding-left: 15px; margin-bottom: 2px; ">
        <?= !strstr($html_header_text, '</p>') ? '<p class="text">'.$html_header_text.'</p>' : $html_header_text ?>
    </div>

    <div id="game_container" class="subbody thread_subbody thread_subbody_full thread_subbody_full_right thread_right event event_triple event_visible" style="text-align: left; position: relative; padding: 10px 10px 6px 15px; margin-bottom: 4px;">

        <div id="game_buttons" data-fieldtype="<?= !empty($this_playerinfo['user_colour_token']) ? $this_playerinfo['user_colour_token'] : 'none' ?>" class="field">

            <a class="link_button robots field_type field_type_<?= $temp_colour_token ?> <?= empty($this_current_token) || $this_current_token == 'robots' ? 'active' : '' ?>" href="<?= MMRPG_CONFIG_ROOTURL.'file/game/robots/' ?>">Robots</a>

            <? if(!empty($temp_show_players)): ?>
                <a class="link_button players field_type field_type_<?= $temp_colour_token ?> <?= $this_current_token == 'players' ? 'active' : '' ?>" href="<?= MMRPG_CONFIG_ROOTURL.'file/game/players/' ?>">Players</a>
            <? endif; ?>

            <a class="link_button database field_type field_type_<?= $temp_colour_token ?> <?= $this_current_token == 'database' ? 'active' : '' ?>" href="<?= MMRPG_CONFIG_ROOTURL.'file/game/database/' ?>">Database</a>

            <? if(!empty($temp_show_items)): ?>
                <a class="link_button items field_type field_type_<?= $temp_colour_token ?> <?= $this_current_token == 'items' ? 'active' : '' ?>" href="<?= MMRPG_CONFIG_ROOTURL.'file/game/items/' ?>">Items</a>
            <? endif; ?>

            <? if(!empty($temp_show_stars)): ?>
                <a class="link_button stars field_type field_type_<?= $temp_colour_token ?> <?= $this_current_token == 'stars' ? 'active' : '' ?>" href="<?= MMRPG_CONFIG_ROOTURL.'file/game/stars/' ?>">Stars</a>
            <? endif; ?>

        </div>

        <?

        // -- LEADERBOARD PAGES -- //

        // Define the allowable pages
        $temp_allowed_pages = array('robots', 'players', 'items', 'stars', 'database');

        // If this is the View Robots page, show the appropriate content
        if (empty($this_current_token) || !in_array($this_current_token, $temp_allowed_pages) || $this_current_token == 'robots'){
            ?>

            <div id="game_frames" class="field view_robots">
                <iframe name="view_robots" src="frames/edit_robots.php?source=website&amp;action=robots&amp;1=1&amp;wap=<?= $flag_wap ? 'true' : 'false' ?>&amp;fadein=false&amp;edit=false<?= !empty($temp_remote_session) ? '&amp;user_id='.$this_playerinfo['user_id'] : '' ?>" width="100%" height="600" frameborder="1" scrolling="no"></iframe>
            </div>

            <?
        }
        // Else if this is the View Players page, show the appropriate content
        elseif ($this_current_token == 'players'){
            ?>

            <div id="game_frames" class="field view_players">
                <iframe name="view_players" src="frames/edit_players.php?source=website&amp;action=players&amp;wap=<?= $flag_wap ? 'true' : 'false' ?>&amp;fadein=false&amp;edit=false<?= !empty($temp_remote_session) ? '&amp;user_id='.$this_playerinfo['user_id'] : '' ?>" width="100%" height="600" frameborder="1" scrolling="no"></iframe>
            </div>

            <?
        }
        // Else if this is the View Items page, show the appropriate content
        elseif ($this_current_token == 'items'){
            ?>

            <div id="game_frames" class="field view_items">
                <iframe name="view_items" src="frames/items.php?source=website&amp;wap=<?= $flag_wap ? 'true' : 'false' ?>&amp;fadein=false&amp;edit=false<?= !empty($temp_remote_session) ? '&amp;user_id='.$this_playerinfo['user_id'] : '' ?>" width="100%" height="600" frameborder="1" scrolling="no"></iframe>
            </div>

            <?
        }
        // Else if this is the View Stars page, show the appropriate content
        elseif ($this_current_token == 'stars'){
            ?>

            <div id="game_frames" class="field view_stars">
                <iframe name="view_stars" src="frames/starforce.php?source=website&amp;wap=<?= $flag_wap ? 'true' : 'false' ?>&amp;fadein=false&amp;edit=false<?= !empty($temp_remote_session) ? '&amp;user_id='.$this_playerinfo['user_id'] : '' ?>" width="100%" height="600" frameborder="1" scrolling="no"></iframe>
            </div>

            <?
        }
        // Else if this is the View Database page, show the appropriate content
        elseif ($this_current_token == 'database'){
            ?>

            <div id="game_frames" class="field view_database">
                <iframe name="view_database" src="frames/database.php?source=website&amp;wap=<?= $flag_wap ? 'true' : 'false' ?>&amp;fadein=false&amp;edit=false<?= !empty($temp_remote_session) ? '&amp;user_id='.$this_playerinfo['user_id'] : '' ?>" width="100%" height="600" frameborder="1" scrolling="no"></iframe>
            </div>

            <?
        }

        ?>

    </div>
<? } ?>


<?
// Clear the form messages if we've made it this far
$_SESSION['mmrpg_form_messages'] = array();
// Collect the buffer and define the page markup
//$this_markup_body = trim(preg_replace('#\s+#', ' ', ob_get_clean()));
$this_markup_body = trim(ob_get_clean());
?>