<?

/*
 * COMMUNITY CATEGORY VIEW
 */

// Collect details for the general chat thread
$chat_thread_id = 1107;
$chat_thread_info = $db->get_array("SELECT
    categories.category_token AS cat,
    threads.thread_id AS id,
    threads.thread_token AS url
    FROM mmrpg_threads AS threads
    LEFT JOIN mmrpg_categories AS categories ON categories.category_id = threads.category_id
    WHERE threads.thread_id = {$chat_thread_id}
    LIMIT 1
    ;");

// Automatically redirect to the appropriate thread if found
if (!empty($chat_thread_info)){
    $chat_redirect_url = MMRPG_CONFIG_ROOTURL.'community/'.$chat_thread_info['cat'].'/'.$chat_thread_info['id'].'/'.$chat_thread_info['url'].'/';
    header('Location: '.$chat_redirect_url);
    exit();
}

// Update the SEO variables for this page
$this_seo_title = $this_category_info['category_name'].' | '.$this_seo_title;
$this_seo_description = strip_tags($this_category_info['category_description']);

// Define the Open Graph variables for this page
$this_graph_data['title'] = 'Community Forums | '.$this_category_info['category_name'];
$this_graph_data['description'] = strip_tags($this_category_info['category_description']);
//$this_graph_data['image'] = MMRPG_CONFIG_ROOTURL.'images/assets/mmrpg-prototype-logo.png';
//$this_graph_data['type'] = 'website';

// Update the MARKUP variables for this page
//$this_markup_header = $this_thread_info['thread_name']; //.' | '.$this_markup_header;

// Require the leaderboard data file
require_once(MMRPG_CONFIG_ROOTDIR.'includes/leaderboard.php');

// Collect all the active sessions for this page
$temp_viewing_category = mmrpg_website_sessions_active('community/'.$this_category_info['category_token'].'/', 3, true);
$temp_viewing_userids = array();
foreach ($temp_viewing_category AS $session){ $temp_viewing_userids[] = $session['user_id']; }

// Create a chat name for them that's valid for the room
$user_chat_name = !empty($this_userinfo['user_name_public']) ? $this_userinfo['user_name_public'] : $this_userinfo['user_name'];
$user_chat_name = preg_replace('/([^-_a-z0-9]+)/i', '', $user_chat_name);

?>
<h2 class="subheader thread_name field_type_<?= MMRPG_SETTINGS_CURRENT_FIELDTYPE ?>">
    <a class="link" style="display: inline;" href="<?= str_replace($this_category_info['category_token'].'/', '', $_GET['this_current_url']) ?>">Community</a> <span class="pipe">&nbsp;&raquo;&nbsp;</span>
    <a class="link" style="display: inline;" href="<?= $_GET['this_current_url'] ?>"><?= $this_category_info['category_name'] ?></a>
</h2>
<div class="subbody">
    <?= mmrpg_website_text_float_robot_markup(MMRPG_SETTINGS_CURRENT_FIELDMECHA, 'right', '0'.mt_rand(0, 2)) ?>
    <p class="text"><?= $this_category_info['category_description'] ?></p>
    <p class="text" style="margin-bottom: 6px;">(!) You will need a Discord account to use the chat, <a href="https://discordapp.com/register?redirect_to=%2Fchannels%342807345782325249%342807977906143237" target="_blank">please create one</a> if you do not have one already</p>
    <p class="text" style="margin-bottom: 6px;">(!) Please see <a href="community/general/1107/official-chat-help-and-guidelines/">this thread</a> for a more thorough overview of the rules for the chat room<br />
    <p class="text type_block type_water" style="width: auto; padding: 6px; clear: both;">
        <strong>MMRPG Discord Invite</strong> : <a href="https://discord.gg/hv5Ht2d" target="_blank">https://discord.gg/hv5Ht2d</a>
    </p>
    <p class="text type_block type_water" style="width: auto; padding: 6px; clear: both;">
        <strong>MMRPG Discord Chat</strong> : <a href="https://discordapp.com/channels/342807345782325249/342807977906143237" target="_blank">https://discordapp.com/channels/342807345782325249/342807977906143237</a>
    </p>
    </p>
</div>
<?

?>