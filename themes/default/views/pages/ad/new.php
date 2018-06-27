<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
	<h1><?=_e('Publish new advertisement')?></h1>
</div>
<div class="row">
	<div class="<?=core::count(Widgets::render('publish_new')) > 0 ? 'col-xs-9' : 'col-xs-12'?>">
		<div class="well">
			<?= FORM::open(Route::url('post_new',array('controller'=>'new','action'=>'index')), array('class'=>'form-horizontal post_new', 'id'=>'publish-new', 'enctype'=>'multipart/form-data'))?>
				<fieldset>
					<? if (Core::config('general.multilingual')) : ?>
						<div class="form-group">
							<div class="col-md-8">
								<?= Form::label('locale', _e('Language'), array('for'=>'locale'))?>
								<?= Form::select('locale', i18n::get_selectable_languages(), Core::request('locale', i18n::$locale), array('class' => 'form-control', 'id' => 'locale', 'required'))?>
							</div>
						</div>
					<? endif ?>

					<div class="form-group">
						<div class="col-md-8">
							<?= FORM::label('title', _e('Title'), array('for'=>'title'))?>
							<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
						</div>
					</div>

					<!-- category select -->
					<div class="form-group">
						<div class="col-md-12">
							<?= FORM::label('category', _e('Category'), array('for'=>'category'))?>
							<div id="category-chained" class="row <?=($id_category === NULL) ? NULL : 'hidden'?>"
								data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>"
								data-price0="<?=i18n::money_format(0)?>"
								<?=(core::config('advertisement.parent_category')) ? 'data-isparent' : NULL?>
							>
								<div id="select-category-template" class="col-md-6 hidden">
									<select class="disable-select2 select-category" placeholder="<?=__('Pick a category...')?>"></select>
								</div>
								<div id="paid-category" class="col-md-12 hidden">
									<span class="help-block" data-title="<?=__('Category %s is a paid category: %d')?>"><span class="text-warning"></span></span>
								</div>
							</div>
							<?if($id_category !== NULL):?>
								<div id="category-edit" class="row">
									<div class="col-md-8">
										<div class="input-group">
											<input class="form-control" type="text" placeholder="<?=$selected_category->name?>" disabled>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button"><?=_e('Select another')?></button>
											</span>
										</div>
									</div>
								</div>
							<?endif?>
							<input id="category-selected" name="category" value="<?=$id_category?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
						</div>
					</div>

					<!-- location select -->
					<?if($form_show['location'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-12">
								<?= FORM::label('locations', _e('Location'), array('for'=>'location'))?>
								<div id="location-chained" class="row <?=($id_location === NULL) ? NULL : 'hidden'?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
									<div id="select-location-template" class="col-md-6 hidden">
										<select class="disable-select2 select-location" placeholder="<?=__('Pick a location...')?>"></select>
									</div>
								</div>
								<?if($id_location !== NULL):?>
									<div id="location-edit" class="row">
										<div class="col-md-8">
											<div class="input-group">
												<input class="form-control" type="text" placeholder="<?=$selected_location->name?>" disabled>
												<span class="input-group-btn">
													<button class="btn btn-default" type="button"><?=_e('Select another')?></button>
												</span>
											</div>
										</div>
									</div>
								<?endif?>
								<input id="location-selected" name="location" value="<?=$id_location?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
							</div>
						</div>
					<?endif?>

					<?if($form_show['description'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-9">
								<?= FORM::label('description', _e('Description'), array('for'=>'description', 'spellcheck'=>TRUE))?>
								<?=FORM::textarea('description', Request::current()->post('description'), array('class'=>'form-control'.((Core::config("advertisement.description_bbcode"))? NULL:' disable-bbcode'),
									'name'=>'description',
									'id'=>'description',
									'rows'=>10,
									'required',
									'data-bannedwords' => (core::config('advertisement.validate_banned_words') AND core::config('advertisement.banned_words') != '') ? json_encode(explode(',', core::config('advertisement.banned_words'))) : '',
									'data-error' => __('This field must not contain banned words ({0})')))?>
							</div>
						</div>
					<?endif?>
					<?if(core::config("advertisement.num_images") > 0 ):?>
						<div class="form-group images"
							data-max-files="<?=core::config("advertisement.num_images") + 1?>"
							data-max-image-size="<?=core::config('image.max_image_size')?>"
							data-image-width="<?=core::config('image.width')?>"
							data-image-height="<?=core::config('image.height') ? core::config('image.height') : ''?>">
							<div class="col-md-12">
								<label><?=_e('Images')?></label>
								<div class="dropzone" id="images-dropzone"></div>
							</div>
						</div>
					<?endif?>
					<?if($form_show['phone'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-4">
								<?= FORM::label('phone', _e('Phone'), array('for'=>'phone'))?>
                                <div>
                                    <?if (Auth::instance()->get_user()):?>
                                        <?=FORM::input('phone', Auth::instance()->get_user()->phone, array('class'=>'form-control', 'id'=>'phone', 'data-country' => core::config('general.country')))?>
                                    <?else:?>
                                        <?=FORM::input('phone', Request::current()->post('phone'), array('class'=>'form-control', 'id'=>'phone', 'data-country' => core::config('general.country')))?>
                                    <?endif?>
                                </div>
							</div>
						</div>
					<?endif?>
					<?if($form_show['address'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-8">
								<?= FORM::label('address', _e('Address'), array('for'=>'address'))?>
								<?if(core::config('advertisement.map_pub_new')):?>
									<?if (Core::is_HTTPS()):?>
										<div class="input-group">
											<?= FORM::input('address', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
											<span class="input-group-btn">
												<button class="btn btn-default locateme" type="button"><?=_e('Locate me')?></button>
											</span>
										</div>
									<?else:?>
										<?=FORM::input('address', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
									<?endif?>
								<?else:?>
									<?= FORM::input('address', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
								<?endif?>
							</div>
						</div>
						<?if(core::config('advertisement.map_pub_new')):?>
							<div class="popin-map-container">
								<div class="map-inner" id="map"
									data-lat="<?=core::config('advertisement.center_lat')?>"
									data-lon="<?=core::config('advertisement.center_lon')?>"
									data-zoom="<?=core::config('advertisement.map_zoom')?>"
									style="height:200px;max-width:400px;">
								</div>
							</div>
							<input type="hidden" name="latitude" id="publish-latitude" value="" disabled>
							<input type="hidden" name="longitude" id="publish-longitude" value="" disabled>
						<?endif?>
					<?endif?>
					<?if($form_show['price'] != FALSE):?>
						<div class="form-group">

							<div class="col-md-4">
								<?= FORM::label('price', _e('Price'), array('for'=>'price'))?>
								<div class="input-prepend">
								<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => html_entity_decode(html_entity_decode(i18n::money_format(1))), 'class' => 'form-control', 'id' => 'price', 'type'=>'text', 'data-error' => __('Please enter only numbers.'), 'data-decimal_point' => i18n::get_decimal_point()))?>
								</div>
							</div>
						</div>
					<?endif?>
					<?if(core::config('payment.stock')):?>
						<div class="form-group">

							<div class="col-md-4">
								<?= FORM::label('stock', _e('In Stock'), array('for'=>'stock'))?>
								<div class="input-prepend">
								<?= FORM::input('stock', Core::post('stock', 1), array('placeholder' => '10', 'class' => 'form-control', 'id' => 'stock', 'type'=>'text'))?>
								</div>
							</div>
						</div>
					<?endif?>
					<?if($form_show['website'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-4">
								<?= FORM::label('website', _e('Website'), array('for'=>'website'))?>
								<?= FORM::input('website', Request::current()->post('website'), array('placeholder' => core::config("general.base_url"), 'class' => 'form-control', 'id' => 'website'))?>
							</div>
						</div>
					<?endif?>
					<?if (!Auth::instance()->get_user()):?>
						<div class="form-group">
							<div class="col-md-4">
								<?= FORM::label('name', _e('Name'), array('for'=>'name'))?>
								<?= FORM::input('name', Request::current()->post('name'), array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<?= FORM::label('email', (core::config('payment.paypal_seller')==1)?_e('Paypal Email'):_e('Email'), array('for'=>'email'))?>
								<?= FORM::input('email', Request::current()->post('email'), array('class'=>'form-control',
									'id'=>'email',
									'type'=>'email',
									'required',
									'placeholder' => (core::config('payment.paypal_seller')==1) ? _e('Paypal Email') : _e('Email'),
									'data-domain' => (core::config('general.email_domains') != '') ? json_encode(explode(',', core::config('general.email_domains'))) : '',
									'data-error' => __('Email must contain a valid email domain')
									))?>
							</div>
						</div>
					<?endif?>
					<?if(core::config('advertisement.tos') != ''):?>
						<div class="form-group">
							<div class="col-md-4">
								<div class="checkbox">
									<label>
									  	<input type="checkbox" required name="tos" id="tos"/>
										<a target="_blank" href="<?=Route::url('page', array('seotitle'=>core::config('advertisement.tos')))?>"> <?=_e('Terms of service')?></a>
									</label>
								</div>
							</div>
						</div>
					<?endif?>
					<?if ($form_show['captcha'] != FALSE):?>
						<div class="form-group">
							<div class="col-md-4">
								<?if (Core::config('general.recaptcha_active')):?>
									<?=Captcha::recaptcha_display()?>
									<div id="recaptcha1"></div>
								<?else:?>
									<?= FORM::label('captcha', _e('Captcha'), array('for'=>'captcha'))?>
									<span id="helpBlock" class="help-block"><?=captcha::image_tag('publish_new')?></span>
									<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required', 'data-error' => __('Captcha is not correct')))?>
								<?endif?>
							</div>
						</div>
					<?endif?>
					<div class="form-actions">
						<?= FORM::button('submit_btn', _e('Publish new'), array('type'=>'submit', 'id' => 'publish-new-btn', 'data-swaltitle' => __('Are you sure?'), 'data-swaltext' => __('It looks like you have been about to publish a new advertisement, if you leave before submitting your changes will be lost.'), 'class'=>'btn btn-primary', 'action'=>Route::url('post_new',array('controller'=>'new','action'=>'index'))))?>
						<?if (!Auth::instance()->get_user()):?>
							<p class="help-block"><?=_e('User account will be created')?></p>
						<?endif?>
						<?if ( ! Core::config('advertisement.leave_alert')):?>
							<input type="hidden" name="leave_alert" value="0" disabled>
						<?endif?>
					</div>
				</fieldset>
			<?= FORM::close()?>
		</div>
		<div class="modal modal-statc fade" id="processing-modal" data-backdrop="static" data-keyboard="false">
			<div class="modal-body">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title"><?=_e('Processing...')?></h4>
						</div>
						<div class="modal-body">
							<div class="progress progress-striped active">
								<div class="progress-bar" style="width: 100%"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<? if(core::count(Widgets::render('publish_new')) > 0) :?>
		<div class="col-md-3 col-sm-12 col-xs-12">
		    <?foreach ( Widgets::render('publish_new') as $widget):?>
		        <div class="panel panel-sidebar <?=get_class($widget->widget)?>">
		            <?=$widget?>
		        </div>
		    <?endforeach?>
		</div>
	<?endif?>
</div>

<?if (core::config("advertisement.num_images") > 0 ):?>
    <?for ($i=0; $i < core::config("advertisement.num_images") ; $i++):?>
        <div class="modal fade" id="<?='urlInputimage'.$i?>" tabindex="-1" role="dialog" aria-labelledby="<?='urlInputimage'.$i?>Label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="imageURL">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="<?='urlInput'.$i?>Label"><?=_e('Insert Image')?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><?=_e('Image URL')?></label>
                                <input name="<?='image'.$i?>" class="note-image-url form-control" type="text">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><?=_e('Insert Image')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?endfor?>
<?endif?>
