<?

// -- SHOP SELLING ROBOTS -- //

// Collect a better robot index with hidden included
$mmrpg_index_robots = rpg_robot::get_index(true, false);

?>

<table class="full" style="margin-bottom: 5px;">
    <colgroup>
        <col width="50%" />
        <col width="50%" />
    </colgroup>
    <thead>
        <tr>
            <th class="left">
                <span class="buy_button buy_button_header">&nbsp;</span>
                <label class="item_quantity item_quantity_header">Own</label>
                <label class="item_price item_price_header">Buy</label>
            </th>
            <th class="right">
                <span class="buy_button buy_button_header">&nbsp;</span>
                <label class="item_quantity item_quantity_header">Own</label>
                <label class="item_price item_price_header">Buy</label>
            </th>
        </tr>
    </thead>
</table>

<div class="scroll_wrapper">

    <table class="full" style="margin-bottom: 5px;">
        <colgroup>
            <col width="50%" />
            <col width="50%" />
        </colgroup>
        <tbody>
            <tr>

                <?

                // Collect the robots for buying and slice/shuffle if nessary
                $robot_list_array = $shop_info['shop_robots']['robots_selling'];

                // Collect the unlocked robots for this game file
                $robot_list_unlocked = !empty($_SESSION[$session_token]['values']['battle_robots']) ? $_SESSION[$session_token]['values']['battle_robots'] : array();

                // Loop through the items and print them one by one
                $robot_counter = 0;
                foreach ($robot_list_array AS $token => $price){
                    if (isset($mmrpg_index_robots[$token])){ $robot_info = $mmrpg_index_robots[$token]; }
                    else { continue; }

                    $robot_info_token = $token;
                    $robot_info_price = $price;
                    $robot_info_name = $robot_info['robot_name'];
                    $robot_info_type = !empty($robot_info['robot_core']) ? $robot_info['robot_core'] : 'none';
                    $robot_info_type_name = !empty($robot_info['robot_core']) ? ucfirst($robot_info['robot_core']) : 'Neutral';
                    if (!empty($robot_info['robot_core2'])){ $robot_info_type .= '_'.$robot_info['robot_core2']; }
                    $robot_info_unlocked = mmrpg_prototype_robot_unlocked('', $robot_info_token);
                    $robot_info_hidden = empty($_SESSION['GAME']['values']['robot_database'][$robot_info_token]['robot_scanned']) ? true : false;
                    $robot_info_exclusive = !empty($robot_info['robot_flag_exclusive']) ? true : false;
                    if ($robot_info_exclusive){
                        $global_item_quantities['robot-'.$robot_info_token] = 1;
                        $global_item_prices['buy']['robot-'.$robot_info_token] = 0;
                        $temp_info_tooltip = 'I\'m sorry but this robot is going to take me a little longer to build than usual.  If you encounter it in battle before I finish, can you try unlocking it the old-fashioned way?';
                        if ($robot_info_hidden){$robot_info_name = preg_replace('/[a-z]{1}/i', '?', $robot_info_name); }
                        //else { $robot_info_name = preg_replace('/[a-z]{1}/i', '-', $robot_info_name); }
                    } elseif ($robot_info_hidden){
                        $global_item_quantities['robot-'.$robot_info_token] = 1;
                        $global_item_prices['buy']['robot-'.$robot_info_token] = 0;
                        $temp_info_tooltip = 'My apologies, but I haven\'t finished this one yet. If you encounter any new '.$robot_info_type_name.' Core robots in battle, would you mind scanning their data for me?';
                        $robot_info_name = preg_replace('/[a-z]{1}/i', '?', $robot_info_name);
                    } else {
                        $global_item_quantities['robot-'.$robot_info_token] = $robot_info_unlocked ? 1 : 0;
                        $global_item_prices['buy']['robot-'.$robot_info_token] = $robot_info_unlocked ? 0 : $robot_info_price;
                        //$temp_info_tooltip = rpg_robot::print_editor_title_markup($player_info, $robot_info);
                        $temp_info_tooltip = $robot_info['robot_name'];
                        if (empty($robot_info['robot_core'])){ $temp_info_tooltip .= ' (Neutral Core)'; }
                        else { $temp_info_tooltip .= ' ('.ucwords($robot_info['robot_core'].(!empty($robot_info['robot_core2']) ? ' / '.$robot_info['robot_core2'] : '')).' Core)'; }
                        $temp_info_tooltip .= ' // [[ ';
                            //$temp_info_tooltip .= 'LE: '.$robot_info['robot_energy'];
                            //$temp_info_tooltip .= '| WE: '.$robot_info['robot_weapons'];
                            $temp_info_tooltip .= 'EN: '.$robot_info['robot_energy'];
                            $temp_info_tooltip .= '| AT: '.$robot_info['robot_attack'];
                            $temp_info_tooltip .= '| DF: '.$robot_info['robot_defense'];
                            $temp_info_tooltip .= '| SP: '.$robot_info['robot_speed'];
                            if (!empty($robot_info['robot_description'])){ $temp_info_tooltip .= ' // '.$robot_info['robot_description'].' '; }
                        $temp_info_tooltip .= ' ]] ';
                        $temp_info_tooltip = htmlentities($temp_info_tooltip, ENT_QUOTES, 'UTF-8', true);
                    }

                    if ($robot_info_unlocked){ $robot_info_price = 0; }

                    $robot_counter++;
                    $robot_cell_float = $robot_counter % 2 == 0 ? 'right' : 'left';

                    $robot_block_purchase = $robot_info_exclusive || $robot_info_hidden ? true : false;

                    ?>
                        <td class="<?= $robot_cell_float ?> item_cell" data-kind="robot" data-action="buy" data-token="<?= 'robot-'.$robot_info_token ?>">
                            <span class="item_name robot_name robot_type robot_type_<?= $robot_info_type ?>" data-tooltip="<?= $temp_info_tooltip ?>"<?= ($robot_block_purchase ? 'style="text-decoration: line-through;"' : '') ?>><?= $robot_info_name ?></span>
                            <a class="buy_button robot_type robot_type_none" href="#">Buy</a>
                            <label class="item_quantity" data-quantity="0"<?= ($robot_block_purchase ? 'style="visibility: hidden;"' : '') ?>><?= !empty($robot_info_quantity) ? '&#10004;' : '-' ?></label>
                            <label class="item_price" data-price="<?= $robot_info_price ?>">&hellip; <?= $robot_info_price ?>z</label>
                        </td>
                    <?

                    if ($robot_cell_float == 'right'){ echo '</tr><tr>'; }

                }

                if ($robot_counter % 2 != 0){
                    ?>
                        <td class="right item_cell item_cell_disabled">
                            &nbsp;
                        </td>
                    <?
                }

                ?>

            </tr>
        </tbody>
    </table>

</div>