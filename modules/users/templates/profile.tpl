{extends file="layout/default.tpl"}
{block name="content"}
<!-- BEGIN PAGE CONTENT-->
<div class="row profile">
   <div class="col-md-12">
      <!--BEGIN TABS-->
      <div class="tabbable tabbable-custom tabbable-full-width">
         <ul class="nav nav-tabs">
            <li class=""><a href="#tab_1_1" data-toggle="tab">Overview</a></li>
            <li class="active"><a href="#tab_1_3" data-toggle="tab">Account</a></li>
            <li><a href="#tab_1_4" data-toggle="tab">Projects</a></li>
            <li><a href="#tab_1_6" data-toggle="tab">Help</a></li>
         </ul>
         <div class="tab-content">
            <div class="tab-pane" id="tab_1_1">
               {include file="`$T_MODULE_TPLPATH`/profile.overview.tpl"}
            </div>
            <!--tab_1_2-->
            <div class="tab-pane active" id="tab_1_3">
               <div class="row profile-account">
                  <div class="col-md-3">
                     <ul class="ver-inline-menu tabbable margin-bottom-10">
                        <li class="active">
                           <a data-toggle="tab" href="#tab_1-1">
                           <i class="icon-cog"></i> 
                           Personal info
                           </a> 
                           <span class="after"></span>
                        </li>
                        <!--
                        <li>
                           <a data-toggle="tab" href="#tab_2-1">
                           <i class="icon-home"></i> 
                              Contact info
                           </a> 
                        </li>
                        
                        <li ><a data-toggle="tab" href="#tab_3-2"><i class="icon-picture"></i> Change Avatar</a></li>
                        -->
                        <li ><a data-toggle="tab" href="#tab_4-3"><i class="icon-lock"></i> Change Password</a></li>
                        <li ><a data-toggle="tab" href="#tab_5-4"><i class="icon-eye-open"></i> Privacity Settings</a></li>
                     </ul>
                  </div>
                  <div class="col-md-9">
                     <div class="tab-content">
                        <div id="tab_1-1" class="tab-pane active">
                           {include file="`$T_MODULE_TPLPATH`/profile.personal.tpl"}
                        </div>
                        <!--
                        <div id="tab_2-1" class="tab-pane active">
                           {include file="`$T_MODULE_TPLPATH`/profile.address.tpl"}
                        </div>
                        -->
                        <div id="tab_3-2" class="tab-pane">
                           <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.</p>
                           <form action="#" role="form">
                              <div class="form-group">
                                 <div class="thumbnail" style="width: 310px;">
                                    <img src="http://www.placehold.it/310x170/EFEFEF/AAAAAA&amp;text=no+image" alt="">
                                 </div>
                                 <div class="margin-top-10 fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-group input-group-fixed">
                                       <span class="input-group-btn">
                                       <span class="uneditable-input">
                                       <i class="icon-file fileupload-exists"></i> 
                                       <span class="fileupload-preview"></span>
                                       </span>
                                       </span>
                                       <span class="btn default btn-file">
                                       <span class="fileupload-new"><i class="icon-paper-clip"></i> Select file</span>
                                       <span class="fileupload-exists"><i class="icon-undo"></i> Change</span>
                                       <input type="file" class="default" />
                                       </span>
                                       <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="icon-trash"></i> Remove</a>
                                    </div>
                                 </div>
                                 <span class="label label-danger">NOTE!</span>
                                 <span>
                                 Attached image thumbnail is
                                 supported in Latest Firefox, Chrome, Opera, 
                                 Safari and Internet Explorer 10 only
                                 </span>
                              </div>
                              <div class="margin-top-10">
                                 <a href="#" class="btn green">Submit</a>
                                 <a href="#" class="btn default">Cancel</a>
                              </div>
                           </form>
                        </div>
                        <div id="tab_4-3" class="tab-pane">
                           {include file="`$T_MODULE_TPLPATH`/profile.password.tpl"}
                         </div>
                        <div id="tab_5-4" class="tab-pane">
                           <form action="#" class="">
                              <table class="table table-bordered table-striped">
                                 <tr>
                                    <td>
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus..
                                    </td>
                                    <td>
                                       <label class="uniform-inline">
                                       <input type="radio" name="optionsRadios1" value="option1" />
                                       Yes
                                       </label>
                                       <label class="uniform-inline">
                                       <input type="radio" name="optionsRadios1" value="option2" checked />
                                       No
                                       </label>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       Enim eiusmod high life accusamus terry richardson ad squid wolf moon
                                    </td>
                                    <td>
                                       <label class="uniform-inline">
                                       <input type="checkbox" value="" /> Yes
                                       </label>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       Enim eiusmod high life accusamus terry richardson ad squid wolf moon
                                    </td>
                                    <td>
                                       <label class="uniform-inline">
                                       <input type="checkbox" value="" /> Yes
                                       </label>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       Enim eiusmod high life accusamus terry richardson ad squid wolf moon
                                    </td>
                                    <td>
                                       <label class="uniform-inline">
                                       <input type="checkbox" value="" /> Yes
                                       </label>
                                    </td>
                                 </tr>
                              </table>
                              <!--end profile-settings-->
                              <div class="margin-top-10">
                                 <a href="#" class="btn green">Save Changes</a>
                                 <a href="#" class="btn default">Cancel</a>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <!--end col-md-9-->                                   
               </div>
            </div>
            <!--end tab-pane-->
            <div class="tab-pane" id="tab_1_4">
               <div class="row">
                  <div class="col-md-12">
                     <div class="add-portfolio">
                        <span>502 Items sold this week</span>
                        <a href="#" class="btn icn-only green">Add a new Project <i class="m-icon-swapright m-icon-white"></i></a>                          
                     </div>
                  </div>
               </div>
               <!--end add-portfolio-->
               <div class="row portfolio-block">
                  <div class="col-md-5">
                     <div class="portfolio-text">
                        <img src="{Plico_GetResource file='img/profile/portfolio/logo_metronic.jpg'}" alt="" />
                        <div class="portfolio-text-info">
                           <h4>Metronic - Responsive Template</h4>
                           <p>Lorem ipsum dolor sit consectetuer adipiscing elit.</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="portfolio-info">
                        Today Sold
                        <span>187</span>
                     </div>
                     <div class="portfolio-info">
                        Total Sold
                        <span>1789</span>
                     </div>
                     <div class="portfolio-info">
                        Earns
                        <span>$37.240</span>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="portfolio-btn">
                        <a href="#" class="btn bigicn-only"><span>Manage</span></a>                      
                     </div>
                  </div>
               </div>
               <!--end row-->
               <div class="row portfolio-block">
                  <div class="col-md-5 portfolio-text">
                     <img src="{Plico_GetResource file='img/profile/portfolio/logo_azteca.jpg'}" alt="" />
                     <div class="portfolio-text-info">
                        <h4>Metronic - Responsive Template</h4>
                        <p>Lorem ipsum dolor sit consectetuer adipiscing elit.</p>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="portfolio-info">
                        Today Sold
                        <span>24</span>
                     </div>
                     <div class="portfolio-info">
                        Total Sold
                        <span>660</span>
                     </div>
                     <div class="portfolio-info">
                        Earns
                        <span>$7.060</span>
                     </div>
                  </div>
                  <div class="col-md-2 portfolio-btn">
                     <a href="#" class="btn bigicn-only"><span>Manage</span></a>                      
                  </div>
               </div>
               <!--end row-->
               <div class="row portfolio-block">
                  <div class="col-md-5 portfolio-text">
                     <img src="{Plico_GetResource file='img/profile/portfolio/logo_conquer.jpg'}" alt="" />
                     <div class="portfolio-text-info">
                        <h4>Metronic - Responsive Template</h4>
                        <p>Lorem ipsum dolor sit consectetuer adipiscing elit.</p>
                     </div>
                  </div>
                  <div class="col-md-5" style="overflow:hidden;">
                     <div class="portfolio-info">
                        Today Sold
                        <span>24</span>
                     </div>
                     <div class="portfolio-info">
                        Total Sold
                        <span>975</span>
                     </div>
                     <div class="portfolio-info">
                        Earns
                        <span>$21.700</span>
                     </div>
                  </div>
                  <div class="col-md-2 portfolio-btn">
                     <a href="#" class="btn bigicn-only"><span>Manage</span></a>                      
                  </div>
               </div>
               <!--end row-->
            </div>
            <!--end tab-pane-->
            <div class="tab-pane" id="tab_1_6">
               <div class="row">
                  <div class="col-md-3">
                     <ul class="ver-inline-menu tabbable margin-bottom-10">
                        <li class="active">
                           <a data-toggle="tab" href="#tab_1">
                           <i class="icon-briefcase"></i> 
                           General Questions
                           </a> 
                           <span class="after"></span>                                    
                        </li>
                        <li><a data-toggle="tab" href="#tab_2"><i class="icon-group"></i> Membership</a></li>
                        <li><a data-toggle="tab" href="#tab_3"><i class="icon-leaf"></i> Terms Of Service</a></li>
                        <li><a data-toggle="tab" href="#tab_1"><i class="icon-info-sign"></i> License Terms</a></li>
                        <li><a data-toggle="tab" href="#tab_2"><i class="icon-tint"></i> Payment Rules</a></li>
                        <li><a data-toggle="tab" href="#tab_3"><i class="icon-plus"></i> Other Questions</a></li>
                     </ul>
                  </div>
                  <div class="col-md-9">
                     <div class="tab-content">
                        <div id="tab_1" class="tab-pane active">
                           <div id="accordion1" class="panel-group">
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_1">
                                       1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_1" class="panel-collapse collapse  in">
                                    <div class="panel-body">
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_2">
                                       2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-success">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_3">
                                       3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_3" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-warning">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_4">
                                       4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_4" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-danger">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_5">
                                       5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_5" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_6">
                                       6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_6" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_7">
                                       7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion1_7" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="tab_2" class="tab-pane">
                           <div id="accordion2" class="panel-group">
                              <div class="panel panel-warning">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_1">
                                       1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_1" class="panel-collapse collapse  in">
                                    <div class="panel-body">
                                       <p>
                                          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                       </p>
                                       <p>
                                          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                       </p>
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-danger">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_2">
                                       2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-success">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_3">
                                       3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_3" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_4">
                                       4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_4" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_5">
                                       5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_5" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_6">
                                       6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_6" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_7">
                                       7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion2_7" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="tab_3" class="tab-pane">
                           <div id="accordion3" class="panel-group">
                              <div class="panel panel-danger">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_1">
                                       1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_1" class="panel-collapse collapse  in">
                                    <div class="panel-body">
                                       <p>
                                          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. 
                                       </p>
                                       <p>
                                          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. 
                                       </p>
                                       <p>
                                          Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                       </p>
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-success">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_2">
                                       2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_3">
                                       3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_3" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
                                       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_4">
                                       4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_4" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_5">
                                       5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_5" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_6">
                                       6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_6" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                    <h4 class="panel-title">
                                       <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_7">
                                       7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
                                       </a>
                                    </h4>
                                 </div>
                                 <div id="accordion3_7" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--end tab-pane-->
         </div>
      </div>
      <!--END TABS-->
   </div>
</div>
<!-- END PAGE CONTENT-->
{/block}