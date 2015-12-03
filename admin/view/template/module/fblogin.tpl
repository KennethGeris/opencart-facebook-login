<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-fblogin" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title2; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fblogin" class="form-horizontal">
                    <?php if (isset($validation)) { ?>
                    <div class="tab-content">		  
                        <div class="tab-pane active" id="tab-general">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <td colspan="2">
                                            <span style='text-align: center;'><b><?php echo $text_licence_info; ?></b></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_transaction_email; ?></td>
                                        <td><input type="text" name="email" value="" /></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input type="hidden" name="fblogin_transaction_id" value="0000" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-modulesetting" data-toggle="tab"><?php echo $tab_modulesetting; ?></a></li>
                        <li><a href="#tab-general" data-toggle="tab"><?php echo $tab_popup; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-modulesetting">
                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="input-status"><?php echo $entry_name; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="modulearray[name]" placeholder="<?php echo $column_name ;?>" value="<?php echo $modulearray['name'];?>" class="form-control">
                                    <?php if ($error_name) { ?>
                                    <div class="text-danger"><?php echo $error_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="modulearray[type]" value="normal">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-6">
                                    <select name="modulearray[status]" id="input-status" class="form-control">
                                        <?php if ($modulearray['status']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="tab-pane" id="tab-general">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_apikey; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="fblogin_apikey" id="fblogin_apikey" size="50" value="<?php echo $fblogin_apikey; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_apisecret; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="fblogin_apisecret" id="fblogin_apisecret" size="50" value="<?php echo $fblogin_apisecret; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_pwdsecret; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="fblogin_pwdsecret" id="fblogin_pwdsecret" size="10" value="<?php echo $fblogin_pwdsecret; ?>" class="form-control"/>
                                    <span class="help"><?php echo $entry_pwdsecret_desc; ?></span>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_customer_group; ?> </label>
                                <div class="col-sm-6">
                                    <select name="fblogin_customer_group" class="form-control">
                                        <?php foreach($customer_groups as $customer_group){ ?>
                                        <option value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ($customer_group['customer_group_id']==$fblogin_customer_group) { ?> selected="selected" <?php }; ?> ><?php echo $customer_group['name']; ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>                            
                        <?php foreach ($languages as $language) { ?>
                        <div class="form-group" id="languagem<?php echo $language['language_id']; ?>">
                            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_fbutton; ?><br>
                                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                                <?php echo $language['name']; ?>
                            </label>                    
                            <div class="col-sm-6">
                                <a href="" id="thumb-image<?php echo $language['language_id']; ?>fb" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb5[$language['language_id']]; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                <input type="hidden" name="fblogin_<?php echo $language['language_id']; ?>_fbutton" value="<?php echo $fblogin[$language['language_id']]['fbutton']; ?>" id="image<?php echo $language['language_id']; ?>5" />
                            </div>
                        </div>

                        <?php } ?>                                     
                    </div>  
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>