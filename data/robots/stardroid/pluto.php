<?
// PLUTO
$robot = array(
    'robot_number' => 'SRN-008',
    'robot_class' => 'boss',
    'robot_game' => 'MM30',
    'robot_name' => 'Pluto',
    'robot_token' => 'pluto',
    'robot_core' => 'swift',
    'robot_core2' => 'impact',
    'robot_description' => 'Pouncing Feline Stardroid',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('freeze', 'crystal'), // grab-buster
    'robot_resistances' => array('impact'),
    'robot_immunities' => array('space'),
    'robot_abilities' => array(
        'break-dash',
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
                array('level' => 0, 'token' => 'break-dash')
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