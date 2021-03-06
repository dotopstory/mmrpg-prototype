<?
// Define a function for loading the game session
function mmrpg_load_game_session(){

    // Reference global variables
    global $db;
    $session_token = mmrpg_game_token();

    // Do NOT load, save, or otherwise alter the game file while viewing remote
    if (defined('MMRPG_REMOTE_GAME')){ return true; }

    // Clear the community thread tracker
    $_SESSION['COMMUNITY']['threads_viewed'] = array();

    // Collect the pending login details if set
    $login_user_id = 0;
    if (!empty($_SESSION[$session_token]['PENDING_LOGIN_ID'])){
        $login_user_id = $_SESSION[$session_token]['PENDING_LOGIN_ID'];
    } elseif (!empty($_SESSION[$session_token]['USER']['userid'])){
        $login_user_id = $_SESSION[$session_token]['USER']['userid'];
    }

    // If this is NOT demo mode, load from database
    $is_demo_mode = rpg_game::is_demo();
    if (!$is_demo_mode && !empty($login_user_id)){

        // LOAD DATABASE INFO

        // Collect the user and save info from the database
        $this_database_save = $db->get_array("SELECT * FROM mmrpg_saves WHERE user_id = {$login_user_id} LIMIT 1");
        $this_database_user = $db->get_array("SELECT * FROM mmrpg_users WHERE user_id = {$login_user_id} LIMIT 1");
        if (empty($this_database_save)){ die('could not load save for file '.$temp_matches[2].' and path '.$temp_matches[1].' on line '.__LINE__); }
        if (empty($this_database_user)){ die('could not load user for '.$this_database_save['user_id'].' on line '.__LINE__); }

        // Update the game session with database extracted variables
        $new_game_data = array();

        $new_game_data['CACHE_DATE'] = $this_database_save['save_cache_date'];

        $new_game_data['USER']['userid'] = $this_database_user['user_id'];
        $new_game_data['USER']['roleid'] = $this_database_user['role_id'];
        $new_game_data['USER']['username'] = $this_database_user['user_name'];
        $new_game_data['USER']['username_clean'] = $this_database_user['user_name_clean'];
        $new_game_data['USER']['password'] = '';
        $new_game_data['USER']['password_encoded'] = '';
        $new_game_data['USER']['omega'] = $this_database_user['user_omega'];
        $new_game_data['USER']['profiletext'] = $this_database_user['user_profile_text'];
        $new_game_data['USER']['creditstext'] = $this_database_user['user_credit_text'];
        $new_game_data['USER']['creditsline'] = $this_database_user['user_credit_line'];
        $new_game_data['USER']['imagepath'] = $this_database_user['user_image_path'];
        $new_game_data['USER']['backgroundpath'] = $this_database_user['user_background_path'];
        $new_game_data['USER']['colourtoken'] = $this_database_user['user_colour_token'];
        $new_game_data['USER']['gender'] = $this_database_user['user_gender'];
        $new_game_data['USER']['displayname'] = $this_database_user['user_name_public'];
        $new_game_data['USER']['emailaddress'] = $this_database_user['user_email_address'];
        $new_game_data['USER']['websiteaddress'] = $this_database_user['user_website_address'];
        $new_game_data['USER']['dateofbirth'] = $this_database_user['user_date_birth'];
        $new_game_data['USER']['approved'] = $this_database_user['user_flag_approved'];

        $new_game_data['counters'] = !empty($this_database_save['save_counters']) ? json_decode($this_database_save['save_counters'], true) : array();
        $new_game_data['values'] = !empty($this_database_save['save_values']) ? json_decode($this_database_save['save_values'], true) : array();

        if (!isset($this_database_save['save_values_battle_index'])){
            $new_game_data['values']['battle_index'] = array();
        }

        if (!empty($this_database_save['save_values_battle_complete'])){
            $new_game_data['values']['battle_complete'] = json_decode($this_database_save['save_values_battle_complete'], true);
            $new_game_data['values']['battle_complete_hash'] = md5($this_database_save['save_values_battle_complete']);
        }

        if (!empty($this_database_save['save_values_battle_failure'])){
            $new_game_data['values']['battle_failure'] = json_decode($this_database_save['save_values_battle_failure'], true);
            $new_game_data['values']['battle_failure_hash'] = md5($this_database_save['save_values_battle_failure']);
        }

        if (!empty($this_database_save['save_values_battle_rewards'])){
            $new_game_data['values']['battle_rewards'] = json_decode($this_database_save['save_values_battle_rewards'], true);
            $new_game_data['values']['battle_rewards_hash'] = md5($this_database_save['save_values_battle_rewards']);
        }

        if (!empty($this_database_save['save_values_battle_settings'])){
            $new_game_data['values']['battle_settings'] = json_decode($this_database_save['save_values_battle_settings'], true);
            $new_game_data['values']['battle_settings_hash'] = md5($this_database_save['save_values_battle_settings']);
        }

        if (!empty($this_database_save['save_values_battle_items'])){
            $new_game_data['values']['battle_items'] = json_decode($this_database_save['save_values_battle_items'], true);
            $new_game_data['values']['battle_items_hash'] = md5($this_database_save['save_values_battle_items']);
        }

        if (!empty($this_database_save['save_values_battle_abilities'])){
            $new_game_data['values']['battle_abilities'] = json_decode($this_database_save['save_values_battle_abilities'], true);
            $new_game_data['values']['battle_abilities_hash'] = md5($this_database_save['save_values_battle_abilities']);
        }

        if (!empty($this_database_save['save_values_battle_stars'])){
            $new_game_data['values']['battle_stars'] = json_decode($this_database_save['save_values_battle_stars'], true);
            $new_game_data['values']['battle_stars_hash'] = md5($this_database_save['save_values_battle_stars']);
        }

        if (!empty($this_database_save['save_values_robot_alts'])){
            $new_game_data['values']['robot_alts'] = json_decode($this_database_save['save_values_robot_alts'], true);
            $new_game_data['values']['robot_alts_hash'] = md5($this_database_save['save_values_robot_alts']);
        }

        if (!empty($this_database_save['save_values_robot_database'])){
            $new_game_data['values']['robot_database'] = json_decode($this_database_save['save_values_robot_database'], true);
            $new_game_data['values']['robot_database_hash'] = md5($this_database_save['save_values_robot_database']);
        }

        $new_game_data['flags'] = !empty($this_database_save['save_flags']) ? json_decode($this_database_save['save_flags'], true) : array();

        $new_game_data['battle_settings'] = !empty($this_database_save['save_settings']) ? json_decode($this_database_save['save_settings'], true) : array();

        // Update the session with the new save info
        $_SESSION[$session_token] = array_merge($_SESSION[$session_token], $new_game_data);
        unset($new_game_data);

        // Unset the player selection to restart at the player select screen
        if (mmrpg_prototype_players_unlocked() > 1){ $_SESSION[$session_token]['battle_settings']['this_player_token'] = false; }

        // Expand user's current IP list, then add a new entry and filter unique
        $local_ips = array('0.0.0.0', '127.0.0.1');
        $ip_list = !empty($this_database_user['user_ip_addresses']) ? $this_database_user['user_ip_addresses'] : '';
        $ip_list = strstr($ip_list, ',') ? explode(',', $ip_list) : array($ip_list);
        $ip_list = array_filter(array_map('trim', $ip_list));
        $ip_list[] = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ $ip_list[] = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])); }
        foreach ($ip_list AS $k => $ip){ if (empty($ip) || in_array($ip, $local_ips)){ unset($ip_list[$k]); } }
        $ip_list = array_unique($ip_list);

        // Update the user table in the database if not done already
        if (empty($_SESSION[$session_token]['DEMO'])){
            $db->update('mmrpg_users', array(
                'user_ip_addresses' => implode(',', $ip_list)
                ), "user_id = {$this_database_user['user_id']}");
        }

        /*
        // Update the user table in the database if not done already
        if (empty($_SESSION[$session_token]['DEMO'])){
            $db->update('mmrpg_users', array(
                'user_last_login' => time(),
                'user_backup_login' => $this_database_user['user_last_login'],
                'user_ip_addresses' => implode(',', $ip_list)
                ), "user_id = {$this_database_user['user_id']}");
        }
        */

        // Clear the pending login ID
        unset($_SESSION[$session_token]['PENDING_LOGIN_ID']);

    }

    // Update the last saved value
    $_SESSION[$session_token]['values']['last_load'] = time();

    // Return true on success
    return true;

}
?>