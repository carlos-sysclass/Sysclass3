<div id="dialogs-enroll-avaliable" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-shield"></i>
                        {translateToken value='Programs Avaliable'}
                        <span data-update="name"></span>
                    </h4>
                </div>
                <style>
                    .thumbnail {
                        /*min-height: 480px;*/
                    }
                </style>
                <div class="modal-body ">
                    <div class="" id="enroll-avaliable-carroussel">
                        <ul class="carroussel">
                            {foreach $T_AVALIABLE_PROGRAMS as $info}
                                {assign var="enrollment" value=$info.enrollment}
                                {assign var="program" value=$info.program}
                                <li class="">
                                    <div class="thumbnail">
                                    {if $program.image}
                                        <img src="{$program.image.url}" style="width: 100%; height: 200px; display: block;">
                                    {else}
                                        <img src="http://placehold.it/300x150&text={translateToken value='No Image'}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    {/if}
                                        <div class="caption">
                                            <h3>{$program.name}</h3>
                                            <p> {$program.description|truncate:160:"...":false nofilter}</p>

                                            {if $program.area_id}
                                                <p>{translateToken value="Departament"}: <strong>{$program.departament.name}</strong></p>
                                            {/if}

                                            {if $program.courses}
                                                <p>{translateToken value="Total Courses"}: <strong>
                                            {$program.courses|@count}</strong></p>
                                            {/if}

                                            {if $program.coordinator_id}
                                                <p>{translateToken value="Coordinator"}: <strong>{$program.coordinator.name} {$program.coordinator.surname}</strong></p>
                                            {/if}

                                            {if $program.language_id}
                                                <p>{translateToken value="Language"}: <strong>{$program.language.name}</strong></p>
                                            {/if}

                                            <p>{translateToken value="Automatic Approval"}: 
                                            {if $enrollment.signup_auto_approval}
                                                <strong class="text-success">{translateToken value="YES"}</strong>
                                            {else}
                                                <strong class="text-danger">{translateToken value="NO"}</strong>
                                            {/if}
                                            </p>

                                            {if $enrollment.signup_auto_approval}
                                                <p>
                                                    <a href="javascript:;" class="btn btn-primary enroll-action" data-enroll-id="{$enrollment.enroll_id}" data-program-id="{$program.id}"> 
                                                    {translateToken value="Enroll"}
                                                    </a>
                                                </p>
                                            {else}
                                                <p>
                                                    <a href="javascript:;" class="btn btn-warning enroll-action" data-enroll-id="{$enrollment.enroll_id}" data-program-id="{$program.id}"> 
                                                    {translateToken value="Request Enroll"}
                                                    </a>
                                                </p>
                                            {/if}
                                        </div>
                                    </div>
                                </li>
                            {/foreach}
                            
                            <li class="">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/300x150&text=Image" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                        <p>
                                            <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/300x150&text=Image" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                        <p>
                                            <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/300x150&text=Image" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                        <p>
                                            <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/300x150&text=Image" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                        <p>
                                            <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/300x150&text=Image" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                        <p>
                                            <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
    			</div>
    		</div>
        </form>
	</div>
</div>