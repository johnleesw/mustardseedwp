<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/15/15
 * Time: 11:09 AM
 */

if ( ! ( current_user_can( "Member" ) || current_user_can( "administrator" ) ) ) {
    echo "Access denied";
    exit;
}

get_header();
the_post();

$fields = array(
    'pipeline_tag' => 'Pipeline Tag',
    'organization' => 'Organization',
    'title' => 'Title',
    'company_website' => 'Company Website',
    'contact_person' => 'Contact Person',
    'email_contact' => 'Email contact',
    'gender_of_founder' => 'Gender of Founder',
    'company_size' => 'Company Size',
    'advisors' => 'Advisors',
    'existing_board_members' => 'Existing Board Members',
    'deal_created' => 'Deal Created',
    'founding_date' => 'Founding Date',
    'founding_date_year' => 'Founding Date Year',
    'founding_date_month' => 'Founding Date Month',
    'short_description' => 'Short Description',
    'action_areas' => 'Action Areas',
    'gics_sector' => 'GICS Sector',
    'eventsource' => 'Event/Source',
    'university_source' => 'University Source',
    'country' => 'Country',
    'continent' => 'Continent',
    'social_or_non_social' => 'Social or Non-Social',
    'finalist' => 'Finalist',
    'audience_vote_rank' => 'Audience Vote Rank',
    'submissionvideo_link' => 'Submission/Video Link',
    'comments' => 'Comments',
    'type_of_round' => 'Type of Round',
    'raise_gbp' => 'Raise GBP',
    'valuation_pre_money_gbp' => 'Valuation (pre-money) GBP',
    'other_co_investorsangels' => 'Other Co-Investors/Angels',
    'acceleratorincubator_program' => 'Accelerator/Incubator Program?',
    'pitch_deck_link' => 'Pitch Deck Link',
    'next_activity_date' => 'Next Activity Date',
    'stage' => 'STAGE',
    'stage_priority' => 'Stage Priority',
    'status' => 'Status',
    'owner' => 'Owner',
    'ms_actionupdate' => 'MS Action/Update',
    'last_activity_date' => 'Last Activity Date',
    'red_flag' => 'Red Flag?',
    'reason_for_flag' => 'Reason for Flag',
    'prioritization' => 'Prioritization',
    'categories' => 'Categories',
);
global $post;
?>

<div class="full-text">

    <div class="inner-title-container">
        <?php if ( $link = get_field('submissionvideo_link') ) { ?>
            <iframe src="<?php echo $link; ?>" width="320" height="240" frameborder="0"></iframe>
        <?php } else { ?>
            &nbsp;
        <?php } ?>
    </div>

    <div class="inner-text">
        <h2>
            <?php the_title(); ?>
        </h2>
        <?php the_content(); ?>

        <?php if ( get_field('company_website') ) : ?>
            <br>
            <a href="<?php echo fix_url(get_field('company_website')); ?>">
                Read more here
            </a>
        <?php endif; ?>

        <br>
        <button class="button place-left" href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=234" data-toggle="modal" data-target="#myModal">
            Contact us here
        </button>

    </div>

</div>

<div id="container" class="container-fluid">
    <div class="inner-container">
        <div class="full-text">
            <table style="width:100%;">

                <tr>
                <?php
                $count = 0;
                $values = get_field("column_select");
                if (isset($values[0])) :
                    foreach ($values as $value) : $count++; ?>

                        <td style="width: 33%">
                            <?php echo $fields[$value] . ':'; ?>
                            <br>
                            <?php echo the_field($value); ?>
                        </td>

                    <?php
                    if ($count >= 3) {
                        $count = 0;
                        echo '</tr><tr><td>&nbsp;</td></tr><tr>';
                    }
                    endforeach;
                endif; ?>
                </tr>

            </table>
        </div>
    </div>
</div>


<?php
get_sidebar();
get_footer();