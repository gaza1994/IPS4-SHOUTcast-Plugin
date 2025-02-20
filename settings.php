//<?php
$groups = \IPS\Member\Group::groups();


$form->addTab('gjradiostats_tab_general');
$form->AddSidebar('
    <h2 class="ipsFieldRow_section">Support</h2>
     <ul class="ipsList_reset ipsPad_half ">
        <li class="ipsType_center"><a href="https://garethjohnstone.co.uk" target="_blank" rel="noreferrer" class="ipsButton  ipsButton_small ipsButton_light">Author</a> <a href="mailto:app-support@garethjohnstone.co.uk" target="_blank" rel="noreferrer" class="ipsButton ipsButton_small ipsButton_light">Email</a></li>
    </ul>');

// Add a text input field for IP
$form->add( new \IPS\Helpers\Form\Text( 'gjradiostats_ip', \IPS\Settings::i()->gjradiostats_ip ?: '' ) );

// Add a text input field for Port
$form->add( new \IPS\Helpers\Form\Number( 'gjradiostats_port', \IPS\Settings::i()->gjradiostats_port ?: '' ) );

// Add a select input field for Visibility
$form->add( new \IPS\Helpers\Form\Select( 'gjradiostats_visibility',\IPS\Settings::i()->gjradiostats_visibility=='*' ? '*' : explode( ',', \IPS\Settings::i()->bd_sr_visibleToGroups ), TRUE, array( 'options' => $groups, 'parse' => 'normal', 'multiple' => true, 'unlimited' => '*', 'unlimitedLang' => 'everyone' ), NULL, NULL, NULL, 'gjradiostats_visibility' ) );

// Add a number input field for Update Polling
$form->add( new \IPS\Helpers\Form\Number( 'gjradiostats_updatepolling', \IPS\Settings::i()->gjradiostats_updatepolling ?: 60 ) );

// Setting for autoplay
$form->add( new \IPS\Helpers\Form\Select( 'gjradiostats_autoplay', \IPS\Settings::i()->gjradiostats_autoplay ?: false, TRUE, array( 'options' => array( false => 'Off', true => 'On' ) ) ) );

// Add debug selectbox On or Off field
$form->add( new \IPS\Helpers\Form\Select( 'gjradiostats_debug', \IPS\Settings::i()->gjradiostats_debug ?: 0, TRUE, array( 'options' => array( 0 => 'Off', 1 => 'On' ) ) ) );

if ( $values = $form->values() )
{
    $form->saveAsSettings();
    \IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=core&module=applications&controller=plugins' ), 'saved' );
}

return $form;