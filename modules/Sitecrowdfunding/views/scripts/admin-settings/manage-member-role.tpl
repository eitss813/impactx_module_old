<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecrowdfunding
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create-edit.tpl 2017-03-27 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $this->tinyMCESEAO()->addJS(); ?>

<h2>
    <?php echo $this->translate('Crowdfunding / Fundraising / Donations Plugin'); ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='seaocore_admin_tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>

<?php if (count($this->navigationManageMemberRole)): ?>
    <div class='seaocore_admin_tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigationManageMemberRole)->render() ?>
    </div>
<?php endif; ?>

<div class='seaocore_settings_form'>
    <div class='settings'>
        <?php
        echo $this->form->render($this);
        ?>
    </div>
</div>

<?php if ($this->manageRoleSettings != 2) : ?>
<br /><br />

<div class='seaocore_settings_form'>
    <div class='settings'>
        <form class="global_form">
            <div>
                <h3><?php echo $this->translate("Project Based Member Roles") ?></h3>
                <p class="form-description">
                    <?php echo $this->translate('Below, you can configure member roles for the various Project. By clicking on "Add", "Edit" and "Delete" respectively, you can add multiple new roles, or edit and delete existing roles. Hence, when a user would go Join a project he will be able to choose the role configured by you for that project.') ?>
                </p>
                <?php if(count($this->projects) > 0):?>
                <table class='admin_table sitepagemember_role_table' width="100%">
                    <thead>
                    <tr>
                        <th>
                            <div class="sitepagemember_role_table_name fleft"><b class="bold"><?php echo $this->translate("Project Name") ?></b></div>
                            <div class="sitepagemember_role_table_value fleft"><b class="bold"><?php echo $this->translate("Roles") ?></b></div>
                            <div class="sitepagemember_role_table_option fleft"><b class="bold"><?php echo $this->translate("Options") ?></b></div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->projects as $project):  ?>

                    <tr>
                        <td>
                            <div class="sitepagemember_role_table_name fleft">
                                <span><b class="bold"><?php echo $project['project_name'];?></b></span>
                            </div>

                            <div class="sitepagemember_role_table_value fleft">
                                <ul class="admin-review-cat">
                                    <?php $reviewcat_exist = 0;?>
                                    <?php if(!empty($project['role_params'])): ?>
                                        <?php $project_id = $project['project_id'];?>
                                        <?php foreach($project['role_params'][$project_id] as $ratingParams): ?>
                                            <?php $reviewcat_exist = 1;?>
                                            <li><?php echo $ratingParams['role_name']; ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                                <?php if($reviewcat_exist == 0):?>
                                ---
                                <?php endif;?>
                            </div>
                            <div class="sitepagemember_role_table_option fleft">
                                <?php if($reviewcat_exist < 1):?>
                                    <a href='<?php echo $this->url(array('action' => 'create-role', 'project_id' => $project['project_id'])) ?>' class="smoothbox" title="<?php echo $this->translate("Add") ?>"><?php echo $this->translate("Add") ?></a>
                                <?php endif; ?>

                                <?php if($reviewcat_exist == 1):?>
                                <?php if($reviewcat_exist < 1):?> | <?php endif; ?><a href='<?php echo $this->url(array('action' => 'edit-role', 'project_id' => $project['project_id'])) ?>' class="smoothbox" title="<?php echo $this->translate("Edit") ?>"><?php echo $this->translate("Edit") ?></a>

                                | <a href='<?php echo $this->url(array('action' => 'delete-role', 'project_id' => $project['project_id'])) ?>' class="smoothbox" title="<?php echo $this->translate("Delete") ?>"><?php echo $this->translate("Delete") ?></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else:?>
                <br/>
                <div class="tip">
                    <span><?php echo $this->translate("There are currently no projects to be mapped.") ?></span>
                </div>
                <?php endif;?>
            </div>
        </form>
    </div>
</div>

<?php else : ?>

    <br /><br />
    <div class="tip">
        <span>
            <?php echo $this->translate('You have allowed only project admins to add roles from the above setting, thus you can not add roles here.');?>
        </span>
    </div>

<?php endif; ?>
