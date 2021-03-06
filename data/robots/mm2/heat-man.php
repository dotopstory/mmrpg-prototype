<?
// HEAT MAN
$robot = array(
    'robot_number' => 'DWN-015',
    'robot_game' => 'MM02',
    'robot_name' => 'Heat Man',
    'robot_token' => 'heat-man',
    'robot_image_editor' => 412,
    'robot_image_alts' => array(
        array('token' => 'alt', 'name' => 'Heat Man (Blue Alt)', 'summons' => 100, 'colour' => 'water'),
        array('token' => 'alt2', 'name' => 'Heat Man (Green Alt)', 'summons' => 200, 'colour' => 'nature'),
        array('token' => 'alt9', 'name' => 'Heat Man (Darkness Alt)', 'summons' => 900, 'colour' => 'empty')
        ),
    'robot_core' => 'flame',
    'robot_description' => 'Armored Flame Robot',
    'robot_field' => 'atomic-furnace',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('water', 'freeze'),
    'robot_resistances' => array('impact'),
    'robot_immunities' => array('flame'),
    'robot_abilities' => array(
        'atomic-fire',
        'buster-shot',
        'attack-boost', 'attack-break', 'attack-swap', 'attack-mode',
        'defense-boost', 'defense-break', 'defense-swap', 'defense-mode',
        'speed-boost', 'speed-break', 'speed-swap', 'speed-mode',
        'energy-boost', 'energy-break', 'energy-swap', 'energy-mode',
        'field-support', 'mecha-support',
        'light-buster', 'wily-buster', 'cossack-buster'
        ),
    'robot_rewards' => array(
        'abilities' => array(
                array('level' => 0, 'token' => 'atomic-fire')
            )
        ),
    'robot_quotes' => array(
        'battle_start' => '',
        'battle_taunt' => '',
        'battle_victory' => '',
        'battle_defeat' => ''
        )
    );
?>