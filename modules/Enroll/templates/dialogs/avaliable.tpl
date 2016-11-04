<div id="dialogs-enroll-avaliable" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-asterisk"></i>
                        {translateToken value='New Programs Avaliable'}
                        <span data-update="name"></span>
                    </h4>
                </div>
                <style>
                    .thumbnail {
                        height: 400px;
                    }
                </style>
                <div class="modal-body ">
                    <div class="">
                        <ul class="carroussel">
                            {foreach $T_AVALIABLE_PROGRAMS as $program}
                                <li class="">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/300x150&text={translateToken value='No Image'}" alt="100%x200" style="width: 100%; height: 200px; display: block;">
                                        <div class="caption">
                                            <h3>{$program.name}</h3>
                                            <p> {$program.description nofilter}</p>
                                            <p>
                                                <a href="javascript:;" class="btn btn-primary"> {translateToken value="Enroll"}</a>
                                            </p>
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
                        </ul>
                    </div>

                    <div class="form-actions nobg">
                        <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
                    </div>
    			</div>
    		</div>
        </form>
	</div>
</div>