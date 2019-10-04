@extends(Config::get('chatter.master_file_extend'))

@php
	$chatter_editor = 'trumbowyg';
@endphp

@section(Config::get('chatter.yields.head'))
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css') }}" rel="stylesheet">
	<link href="{{ url('/vendor/devdojo/chatter/assets/css/chatter.css') }}" rel="stylesheet">
	@if($chatter_editor == 'simplemde')
		<link href="{{ url('/vendor/devdojo/chatter/assets/css/simplemde.min.css') }}" rel="stylesheet">
	@elseif($chatter_editor == 'trumbowyg')
		<link href="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/ui/trumbowyg.css') }}" rel="stylesheet">
		<style>
			.trumbowyg-box, .trumbowyg-editor {
				margin: 0px auto;
			}
		</style>
	@endif
@stop

@php

	$url ='http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	//VARS
	$categorie_name =[
		"",
		'Server Announcements',
		 'Support',
		 'Information',
		 'Whitelist Server',
		 'Properties of San Andreas',
		 'Black Market',
		 'Los Santos Advertisement',
		 'Faction News',
		 'Factions of Los Santos',
		 'Gang Discussions',
		 'Gangs of Los Santos',
		 'Applications',
		 'General Discussions',
		 'Screenshot & Videos',
		 'Help Section',
		 'Multimedia Section',
		 'Administrative Request',
		 'Donation Request',
		 'Refund Request',
		 'Custom Unsertitle Request',
		 'Ban Appeals',
		 'Report a Player',
		 'Report a Bug',
		 'Suggestions',
		 'Development Team',
		 'Skins',
		 'Vehicles',
		 'Weapons',
		 'Other',
		];
	$slugs;
	$high_categpory[5]="In-Character";
	$high_categpory[8]="Factions Of No Limit Roleplay";
	$high_categpory[10]="Gangs Of No Limit Roleplay";
	$high_categpory[13]="No Limit Roleplay";
	$high_categpory[17]="Out-Of-Character";
	$high_categpory[23]="No Limit Roleplay Development";
	$high_categpory[26]="GTA Modifications";
	$total_post = 0;
	$total_messages = 0;
	$all_messages = 0;
	$total_users = 0;
	//CHATKIT API MESSAGES FETCH
	$chatkit = app('ChatKit');
	// Get messages via Chatkit
	
	if(Auth::check()){
		$fetchMessages = $chatkit->getRoomMessages([
                'room_id' => 'e01b3fa9-3295-4178-9521-dbd5d5757cbd',
                'direction' => 'older',
                'limit' => 40
            ]);

            $messages = collect($fetchMessages['body'])->map(function ($message,$user) {
				$avatar = 'DELETED';
				$name = 'DELETED';
				if(\DB::table('users')->select('name')->where('email','=',$message['user_id'])->first()){
					$name= \DB::table('users')->select('name')->where('email','=',$message['user_id'])->first()->name;
				}
				if($avatar = \DB::table('users')->select('profile_image')->where('email',$message['user_id'])->first()){
					$avatar = \DB::table('users')->select('profile_image')->where('email',$message['user_id'])->first()->profile_image;
				}
				return [
                    'id' => $message['id'],
					'senderId' => $message['user_id'],
					'name'  => $name,
                    'text' => $message['text'],
					'timestamp' => $message['created_at'],
					'avatar' => $avatar,
                ];
			});

	}
            
			$db_users = \App\User::all();


			$users = collect($db_users)
			->map(function($user) {
				return[
					'name' => $user['name'],
					'id' => $user['id'],
					'avatar' => $user['profile_image'],
					'email' => $user['email'],
				];
			});
			$chatkit_users = [];
			$all_discussions = DevDojo\Chatter\Models\Models::Discussion()->all();
			$last_user = $latestUser = App\User::latest()->first();
			$most_viewed = DevDojo\Chatter\Models\Models::Discussion()->orderBy('views','DESC')->get();
@endphp

@section('content')
<div id="chatter" class="chatter_home">
	@if (Auth::check())
		@foreach ($db_users as $user)
			@php
				array_push($chatkit_users,$chatkit->getUser([ 'id' => $user->email ]));
				$total_users++;
			@endphp
		@endforeach
	@endif
	<div id="chatter_hero">
		<div id="chatter_hero_dimmer"></div>
		<?php $headline_logo = Config::get('chatter.headline_logo'); ?>
		@if( isset( $headline_logo ) && !empty( $headline_logo ) )
			<img src="/storage/logo2.png">
		@else
			<h1>@lang('chatter::intro.headline')</h1>
			<p>@lang('chatter::intro.description')</p>
		@endif
	</div>
	@if(config('chatter.errors'))
		@if(Session::has('chatter_alert'))
			<div class="chatter-alert alert alert-{{ Session::get('chatter_alert_type') }}">
				<div class="container">
					<strong><i class="chatter-alert-{{ Session::get('chatter_alert_type') }}"></i> {{ Config::get('chatter.alert_messages.' . Session::get('chatter_alert_type')) }}</strong>
					{{ Session::get('chatter_alert') }}
					<i class="chatter-close"></i>
				</div>
			</div>
			<div class="chatter-alert-spacer"></div>
		@endif

		@if (count($errors) > 0)
			<div class="chatter-alert alert alert-danger">
				<div class="container">
					<p><strong><i class="chatter-alert-danger"></i> @lang('chatter::alert.danger.title')</strong> @lang('chatter::alert.danger.reason.errors')</p>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
	@endif
	
	<div class="container chatter_container">
		<br>
		<div class="row flex-justify-center">
			<div class="col-md-3" id="discussion_cat">
	    		<!-- SIDEBAR -->
	    		<div class="chatter_sidebar">
				<!-- END SIDEBAR -->
				</div>
	    	</div>
		</div>
		<div class="row">
			<div class="col-md-12">				
					<div  class="flex-center position-ref">
						<div class="top-right links">
						</div>
							<div class="row justify-content-center flex-wrap" style="width: 100%;">
								<div class="col-md-7 chatbox">									
									<div class="panel">  
										<div class="panel-heading panel-heading-bg"> 
												<div class="panel-title category_title"><p class="text-white" style="text-align: left;">No Limit Roleplay Chatroom</p></div>
										</div>
										@if (Auth::check())
											<chatbox user-id='{{Auth::user()->email}}' room-id='e01b3fa9-3295-4178-9521-dbd5d5757cbd' :initial-messages='@json($messages)' :room-Users='@json($users)' :user-chatkit='@json($chatkit_users)'> </chatbox>
										@else
											@php($rand = rand(1,9))
											<div  id="chatbox_offline" class="panel-body" style="overflow-y: scroll;text-align: left;height: 503px;width:100%;background: rgba(68, 71, 104,.7);">
												 <dl>
													<ul>
														<li>
															<dd><img src="/storage/{{'default'.$rand.".jpg"}}"></dd>
														</li>
														<li>
														<dt><strong style="font-size:16px;">{{ "BOT #".$rand }}</strong></dt>
															<dd><p style="word-wrap:normal;">Hello. Welcome to our forum.</p></dd>
															<dd style="color: #DEDEDE;"><ion-icon name="time"></ion-icon>&nbsp;Now</dd>
														</li>
													</ul>
												</dl>
												<dl>
													<ul>
														<li>
															<dd><img src="/storage/{{'default'.$rand.".jpg"}}"></dd>
														</li>
														<li>
														<dt><strong style="font-size:16px;">{{ "BOT #".$rand }}</strong></dt>
															<dd><p style="word-wrap: normal;">You may be looking the chat, right?</p></dd>
															<dd style="color: #DEDEDE;"><ion-icon name="time"></ion-icon>&nbsp;Now</dd>
														</li>
													</ul>
												</dl>
												<dl>
													<ul>
														<li>
															<dd><img src="/storage/{{'default'.$rand.".jpg"}}"></dd>
														</li>
														<li>
														<dt><strong style="font-size:16px;">{{ "BOT #".$rand }}</strong></dt>
															<dd><p style="word-wrap: normal;">In that case you must <a href="/login">log into your account</a> to use this feature. :)</p></dd>
															<dd style="color: #DEDEDE;"><ion-icon name="time"></ion-icon>&nbsp;Now</dd>
														</li>
													</ul>
												</dl>
												<dl>
													<ul>
														<li>
															<dd><img src="/storage/{{'default'.$rand.".jpg"}}"></dd>
														</li>
														<li>
														<dt><strong style="font-size:16px;">{{ "BOT #".$rand }}</strong></dt>
															<dd>Or you can just <a href="/register">create an account</a> if you don't have one. :)</dd>
															<dd style="color: #DEDEDE;"><ion-icon name="time"></ion-icon>&nbsp;Now</dd>
														</li>
													</ul>
												</dl>
											</div>
											<div id="send_form" class="col-md-12" style="background: rgba(68, 71, 104, 0.7);margin: 0;">
												<div class="input-group" style="width:75%;">
													<input type="text" class="form-control" placeholder="Log in to send a new message..." disabled>
												</div>
												<div class="input-group" style="width:15%;transform: translateY(-1px);">
													<button class="btn btn-primary" disabled>Send</button>
												</div>
											</div>
										@endif
									</div>
								</div>					
								<div class="col-md-4">
									<div class="row">
										<div class="panel" style="width: 100%;">
											<div class="panel-heading panel-heading-bg" style="height: 60px;">
												<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">Discord</p></div>
											</div>
											<div class="panel-body" style="background: rgba(68, 71, 104, 0.7);">
												<iframe src="https://discordapp.com/widget?id=625082731688230933&theme=dark" style="width:100%;height: 295px;" allowtransparency="true" frameborder="0"></iframe>											</div>
										</div>																				
									</div>
									<div class="row">
											<div class="panel" style="width: 100%;">
											<div class="panel-heading panel-heading-bg" style="height: 60px;">
												<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">Donations</p></div>
											</div>
											<div class="panel-body" style="background: rgba(68, 71, 104, 0.7); padding: 11.8px;">
													<div class="col-md-12">
														<a class="text-white" style="font-size:18px;font-weight:bold;">Our Paypal</a>
														<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
															<input type="hidden" name="cmd" value="_donations" />
															<input type="hidden" name="business" value="azukadizero@gmail.com" />
															<input type="hidden" name="currency_code" value="USD" />
															<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
															<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
														</form>
													</div>
												
													<div class="col-md-12" style="margin-top:10px;">
														<a class="text-white" style="font-size:18px;font-weight:bold;">Our Patreon</a>
														<a href="https://www.patreon.com/bePatron?u=24736177" data-patreon-widget-type="become-patron-button">Suport us on Patreon</a>
													</div>																			
												
											</div>
										</div>
									</div>								
								</div>
							</div>
					</div>    
					
			</div>
		</div>
		<div class="row flex-wrap" id="fix-me">    	
				<div class="col-md-7">
					<div class="panel">
						<div class="panel-heading panel-heading-bg">
							<div class="panel-title category_title">
								<p class="text-white" style="text-align: left;">Recent Activity</p>
							</div>			
						</div>
						@foreach($discussions as $discussion)
						
						@php ($total_messages+=$discussion->postsCount[0]->total)
						@endforeach
							<ul class="discussions">
									
								@php($count_post=0)
									@foreach($discussions as $discussion)
										@php($count_post++)
										
										@php($pic = json_decode($discussion->picture($discussion->user_id)))							
										@if ($discussion->chatter_category_id>=1)
											<li>
												<a class="discussion_list" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion->category->slug }}/{{ $discussion->slug }}">
													<div class="chatter_avatar">
														@if(Config::get('chatter.user.avatar_image_database_field'))
			
															<?php $db_field = Config::get('chatter.user.avatar_image_database_field'); ?>
			
															<!-- If the user db field contains http:// or https:// we don't need to use the relative path to the image assets -->
															@if( (substr($discussion->user->{$db_field}, 0, 7) == 'http://') || (substr($discussion->user->{$db_field}, 0, 8) == 'https://') )
																<img src="{{ $discussion->user->{$db_field}  }}">
															@else
																<img src="{{ Config::get('chatter.user.relative_url_to_image_assets') . $discussion->user->{$db_field}  }}">
															@endif
			
														@else
			
															<span class="chatter_avatar_circle">
																<img src="/storage/{{$pic[0]->profile_image}}">
															</span>
			
														@endif
													</div>
													<div class="chatter_middle">
														<h3 class="chatter_middle_title">{{ $discussion->title }} <div class="chatter_cat" style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</div></h3>
														<span class="chatter_middle_details">@lang('chatter::messages.discussion.posted_by') <span data-href="/user">{{ ucfirst($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}) }}</span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</span>
														@if($discussion->post[0]->markdown)
															<?php $discussion_body = GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $discussion->post[0]->body ); ?>
														@else
															<?php $discussion_body = $discussion->post[0]->body; ?>
														@endif
														<p>{{ substr(strip_tags($discussion_body), 0, 200) }}@if(strlen(strip_tags($discussion_body)) > 200){{ '...' }}@endif</p>
													</div>
			
													<div class="chatter_right">
			
														<div class="chatter_count"><i class="chatter-bubble"></i> {{ $discussion->postsCount[0]->total }}</div>
													</div>
			
													<div class="chatter_clear"></div>
												</a>
											</li>
										@endif
									@endforeach
								</ul>
								@if (strpos($url,'category')===true)
									<div id="pagination">
								@elseif(strpos($url,'category')===false)
									<div id="pagination" style="display:none">
								@endif
									{{ $discussions->links() }}
								</div>
							</div>
						</div>
						@if (strpos($url,'category') === false)
						<div class="col-md-4">
								<div class="row adjust">
									<div class="panel" style="width: 100%;    background: rgba(68, 71, 104, 0.7);">
										<div class="panel-heading panel-heading-bg" style="height: 60px;">
											<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">Online Members</p></div>
										</div>	
											@if (Auth::check())
												<users user-id='{{Auth::user()->email}}' room-id='e01b3fa9-3295-4178-9521-dbd5d5757cbd' :user-chatkit='@json($chatkit_users)'> </users>				
											@else
											<div class="panel-body" style="width:100%;background: rgba(68, 71, 104, 0.7);">
												<div id="users_online" class="col-md-12" style="    min-height: 80px;">
													<dl>
														<div class="row">
															<div class="col-md-12">
																<ul>
																	<li style="position: relative;">
																		<dt><strong style="font-size:16px;">Please <a href="/login">Sign in to your account</a> to see the online members.</strong></dt>
																	</li>
																</ul>
															</div>
														</div>
													</dl>
												</div>     
											</div>
											@endif
										</div>					
									</div>
									@for ($i = 1; $i < 30; $i++)
										@php ($servernews = 0)
										@php ($total_discussions = 0)
										@php ($title = "")
										@foreach($all_discussions as $discussion)
											@if ($discussion->chatter_category_id==$i)
												@php ($servernews+=$discussion->postsCount[0]->total)
												@php ($total_discussions++)
												@php ($total_post++)
												@php ($all_messages+=$discussion->postsCount[0]->total)
												@php ($title = $discussion->last($i)->title)
												@php ($user = $discussion->username($discussion->last($i)->user_id))
												@php ($created = $discussion->last($i)->created_at)	
											@endif
										@endforeach
									@endfor
									<div class="row adjust">
										<div class="panel" style="width: 100%;    background: rgba(68, 71, 104, 0.7);">
											<div class="panel-heading panel-heading-bg" style="height: 60px;">
												<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">No Limit's Stadistics</p></div>
											</div>	
												<div class="panel-body" style="width:100%;background: rgba(68, 71, 104, 0.7);">
													<div id="users_online" class="col-md-12" style="min-height: 80px;height: 100px;overflow-y: hidden;">
														<dl>
															<div class="row">
																<div class="col-md-12" style="width: 100%;">
																	<ul>
																		<li style="position: relative;display:block">
																		<dt><strong style="font-size:16px;">Discussions </strong><p style="margin-left:auto;position: absolute;;display:inline-block;right: 0;top: 50%;transform: translateY(-50%);">{{$total_post}}</p></dt>
																		</li>
																		<li style="position: relative;margin-left: 0px;display:block">
																		<dt><strong style="font-size:16px;">Messages </strong><p style="margin-left:auto;position: absolute;;display:inline-block;right: 0;top: 50%;transform: translateY(-50%);">{{$all_messages}}</p></dt>
																		</li>
																		<li style="position: relative;margin-left: 0px;display:block">
																		<dt><strong style="font-size:16px;">Members </strong><p style="margin-left:auto;position: absolute;;display:inline-block;right: 0;top: 50%;transform: translateY(-50%);">{{$total_users}}</p></dt>
																		</li>
																		<li style="position: relative;margin-left: 0px;display:block">
																		<dt><strong style="font-size:16px;">Last Member </strong><p style="margin-left:auto;position: absolute;;display:inline-block;right: 0;top: 50%;transform: translateY(-50%);">{{$last_user->name}}</p></dt>
																		</li>
																	</ul>
																</div>
															</div>
														</dl>
													</div>     
												</div>
											</div>					
									</div>
									<div class="row adjust">
										<div class="panel" style="width: 100%;    background: rgba(68, 71, 104, 0.7);">
											<div class="panel-heading panel-heading-bg" style="height: 60px;">
												<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">Share this website!</p></div>
											</div>	
												<div class="panel-body" style="width:100%;background: rgba(68, 71, 104, 0.7);">
													<div id="users_online" class="col-md-12" style="min-height: 80px;height: 100px;overflow-y: hidden;">
														<dl>
															<div class="row">
																<div class="col-md-12" style="width: 100%;">
																	<ul>
																		<li style="position: relative;display:block">
																				<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>																		
																			</li>
																		
																				<br>
																		<li style="position: relative;margin-left: 0px;display:block">
																				<a  href="https://twitter.com/intent/tweet?button_hashtag=NoLimitRoleplay">
																					<button class="btn btn-twitter">
																						<ul style="    margin-top: 3px;">
																							<li>
																								<ion-icon name="logo-twitter" style="vertical-align: middle;color:white;display:inline-block;"></ion-icon>

																							</li>
																							<li style="margin-left: 20px;">
																									<p style="display:inline-block;color:white;">#NoLimitRoleplay</p>
																							</li>
																						</ul>
																					</button>
																				
																			</a>
																		</li>
																		
																	</ul>
																</div>
															</div>
														</dl>
													</div>     
												</div>
											</div>					
									</div>																					
								</div>
							</div>
						@endif
	    @if (strpos($url,'category') === false)
		<div class="row flex-wrap" id="fix-me">    	
				<div class="col-md-7 col-adjust">
						<div class="panel-heading panel-heading-bg">
							<div class="panel-title category_title">
								<p class="text-white" style="text-align: left;">FiveM Server Announcements</p>
							</div>			
						</div>
						
						@for ($i = 1; $i < 30; $i++)
							@php ($servernews = 0)
							@php ($total_discussions = 0)
							@php ($title = "")			
							@foreach($all_discussions as $discussion)
								@if ($discussion->chatter_category_id==$i)
									@php ($servernews+=$discussion->postsCount[0]->total)
									@php ($total_discussions++)
									@php ($total_post++)
									@php ($category_slug = $discussion->findSlug($discussion->last($i)->chatter_category_id))
									@php ($slug = $discussion->last($i)->slug)
									@php ($title = $discussion->last($i)->title)
									@php ($category_link = $discussion->findSlug($i))
									@php ($user = $discussion->username($discussion->last($i)->user_id))
									@php ($created = $discussion->last($i)->created_at)	
								@endif
							@endforeach
							
							@if ($i == 5 || $i == 8 || $i == 10 || $i == 13 || $i == 17 || $i == 23 || $i == 26)
								<div class="panel-heading panel-heading-bg">
									<div class="panel-title category_title">
										<p class="text-white" style="text-align: left;">{{$high_categpory[$i]}}</p>
									</div>			
								</div>	
							@endif
							<div class="category_name panel-heading category_bg d-flex flex-row justify-content-between flex-wrap" style="margin-top:1px;">
									<ul class="forum_category">
										<li>
											<md-chatboxes-icon style="font-size:30px;margin:10px 10px;display: block;vertical-align: middle;"></ion-icon>
			
										</li>
										<li>
											<div class="chatter_count" >
											<a href="/forums/category/{{$category_link[0]->slug}}" class="category text-white" style="font-size: 20px;display: inline-flex;vertical-align: top; font-weight:bold;">{{$categorie_name[$i]}}</a>
												<ul>
													<li>
														<a class="discussions text-white" style="display:flex;vertical-align: top; font-weight:bold;font-size:15px;">Discussions: {{$total_discussions}}</a>
													</li>
													<li>
														<a class="messages text-white" style="display:inline-flex;vertical-align: top; font-weight:bold;font-size:15px;margin-left:10px;margin-top: -2px;">Messages: {{$servernews}}</a>
													</li>
												</ul>								
											</div>
										</li>							
									</ul>
									@if ($title=="")
										<div class="d-flex justify-content-end panel-heading-bg category_adjust" style="padding: 10px 10px;    max-width: 270px;">
											<a   class="text-white latest category_crop" >Latest: &nbsp;<p class="crop"><strong style="font-size:15px;">Nothing Posted Yet...</strong><br><strong style="font-size:15px;"></span></strong></p></a>
										</div>
									@else
										<div class="d-flex justify-content-end panel-heading-bg category_adjust" style="padding: 10px 10px;    max-width: 270px;">
											<a href="/forums/discussion/{{ $category_slug[0]->slug }}/{{ $slug }}" class="text-white latest category_crop latest_posted" >Latest: &nbsp;<p class="crop"><strong style="font-size:15px;">{{$title}}</strong><br><strong style="font-size:15px;"><span>{{ $user[0]->name}}</span> - {{ \Carbon\Carbon::createFromTimeStamp(strtotime($created))->diffForHumans()}}</span></strong></p></a>
										</div>
									@endif
								</div>
								

						@endfor
													
					</div>
						
	
				@if (strpos($url,'category') === false)
					
				
				<div class="col-md-4 mt-adjust">					
						<div class="row adjust mt-adjust">
						<div class="panel" style="width: 100%;    background: rgba(68, 71, 104, 0.7);">
							<div class="panel-heading panel-heading-bg" style="height: 60px;">
								<div class="panel-title category_title"><p class="text-white v__align" style="text-align: left;">Most Viewed Discussions</p></div>
							</div>	
								<div class="panel-body" style="width:100%;background: rgba(68, 71, 104, 0.7);">
									<div id="users_online" class="col-md-12" style="    min-height: 80px;    height: 300px;">
										<dl>
											<div class="row">
												<div class="col-md-12" style="width:100%;">
													<ul class="discussions">
														@php($count = 0)
														@foreach ($most_viewed as $discussion_)
														@php($pic = json_decode($discussion_->picture($discussion->user_id)))	
															@php($count++)
															@if ($count === 10)
																@break
															@else
																<li style="position: relative;display:block;margin-left:0px;height: 90px;">
																		<a class="discussion_list most_viewed" style="margin-top: 10px;margin-bottom:10px;position:absolute;top:0;" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion_->category->slug }}/{{ $discussion_->slug }}">
																			<div class="chatter_avatar avatar_small">
																				@if(Config::get('chatter.user.avatar_image_database_field'))
									
																					<?php $db_field = Config::get('chatter.user.avatar_image_database_field'); ?>
									
																					<!-- If the user db field contains http:// or https:// we don't need to use the relative path to the image assets -->
																					@if( (substr($discussion_->user->{$db_field}, 0, 7) == 'http://') || (substr($discussion_->user->{$db_field}, 0, 8) == 'https://') )
																						<img src="{{ $discussion_->user->{$db_field}  }}">
																					@else
																						<img src="{{ Config::get('chatter.user.relative_url_to_image_assets') . $discussion_->user->{$db_field}  }}">
																					@endif
									
																				@else
									
																					<span class="chatter_avatar_circle avatar_small">
																						<img class=" avatar_small" src="/storage/{{$pic[0]->profile_image}}">
																					</span>
									
																				@endif
																			</div>
																			<div class="chatter_middle title_small">
																				<h3 class="chatter_middle_title "><p>{{ $discussion_->title }}</p> <div class="chatter_cat" style="background-color:{{ $discussion_->category->color }}">{{ $discussion_->category->name }}</div></h3>
																				<span class="chatter_middle_details">@lang('chatter::messages.discussion.posted_by') <span data-href="/user">{{ ucfirst($discussion_->user->{Config::get('chatter.user.database_field_with_user_name')}) }}</span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</span>																				
																			</div>
									
																			<div class="chatter_right">
									
																				<div class="chatter_count" style="    transform: translate(45px,0px);text-align:center;"><ion-icon class="fix" name="eye"></ion-icon> <br><p class="horiz_align">{{ $discussion_->views }}</p></div>
																			</div>
									
																			<div class="chatter_clear"></div>
																			
																		</a>
																		<br>
																</li>
															@endif
														@endforeach
														
														
													</ul>
												</div>
											</div>
										</dl>
									</div>     
								</div>
							</div>					
						</div>																				
					</div>
					@endif
				</div>
		@endif
	    </div>

	<div id="new_discussion">


    	<div class="chatter_loader dark" id="new_discussion_loader">
		    <div></div>
		</div>

    	<form id="chatter_form_editor" action="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}" method="POST">
        	<div class="row">
	        	<div class="col-md-7">
		        	<!-- TITLE -->
	                <input type="text" class="form-control" id="title" name="title" placeholder="@lang('chatter::messages.editor.title')" value="{{ old('title') }}" >
	            </div>

	            <div class="col-md-4">
		            <!-- CATEGORY -->
					<select id="chatter_category_id" class="form-control" name="chatter_category_id">
						<option value="">@lang('chatter::messages.editor.select')</option>
						@foreach($categories as $category)
							@if(old('chatter_category_id') == $category->id)
								<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
							@elseif(!empty($current_category_id) && $current_category_id == $category->id)
								<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
							@else
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endif
						@endforeach
					</select>
		        </div>

		        <div class="col-md-1">
		        	<i class="chatter-close"></i>
		        </div>
	        </div><!-- .row -->

            <!-- BODY -->
        	<div id="editor">
        		@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
					<label id="tinymce_placeholder">@lang('chatter::messages.editor.tinymce_placeholder')</label>
    				<textarea id="body" class="richText" name="body" placeholder="">{{ old('body') }}</textarea>
    			@elseif($chatter_editor == 'simplemde')
    				<textarea id="simplemde" name="body" placeholder="">{{ old('body') }}</textarea>
				@elseif($chatter_editor == 'trumbowyg')
					<textarea class="trumbowyg" name="body" placeholder="@lang('chatter::messages.editor.tinymce_placeholder')">{{ old('body') }}</textarea>
				@endif
    		</div>

            <input type="hidden" name="_token" id="csrf_token_field" value="{{ csrf_token() }}">

            <div id="new_discussion_footer">
            	<input type='text' id="color" name="color" /><span class="select_color_text" style="color: #636b6f;">@lang('chatter::messages.editor.select_color_text')</span>
				<button id="submit_discussion" class="btn btn-primary pull-right">
					<ul style="margin: 0;">
						<li style="display: inline-block">
							<ion-icon name="create" style="vertical-align:middle;"></ion-icon>
						</li>
						<li style="display: inline-block">
							<p style="margin:0;"> @lang('chatter::messages.discussion.create')</p>
						</li>
					</ul>
				</button>
				<a href="/{{ Config::get('chatter.routes.home') }}" class="btn btn-default pull-right" id="cancel_discussion">
					<ul style="margin: 0;">
						<li style="display: inline-block">
							<ion-icon name="close-circle" style="vertical-align:middle;"></ion-icon>
						</li>
						<li style="display: inline-block">
							<p style="margin:0;">@lang('chatter::messages.words.cancel')</p>
						</li>
					</ul>
				</a>
            	<div style="clear:both"></div>
            </div>
        </form>

    </div><!-- #new_discussion -->

</div>

@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
	<input type="hidden" id="chatter_tinymce_toolbar" value="{{ Config::get('chatter.tinymce.toolbar') }}">
	<input type="hidden" id="chatter_tinymce_plugins" value="{{ Config::get('chatter.tinymce.plugins') }}">
@endif
<input type="hidden" id="current_path" value="{{ Request::path() }}">

@endsection

@section(Config::get('chatter.yields.footer'))


@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/tinymce/tinymce.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/tinymce.js') }}"></script>
	<script>
		var my_tinymce = tinyMCE;
		$('document').ready(function(){
			$('#tinymce_placeholder').click(function(){
				my_tinymce.activeEditor.focus();
			});
		});
	</script>
@elseif($chatter_editor == 'simplemde')
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/simplemde.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter_simplemde.js') }}"></script>
@elseif($chatter_editor == 'trumbowyg')
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/trumbowyg.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/trumbowyg.js') }}"></script>
@endif

<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.js') }}"></script>
<script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter.js') }}"></script>
<script>
	$('document').ready(function(){

		$('.chatter-close, #cancel_discussion').click(function(){
			$('#new_discussion').slideUp();
		});
		$('#new_discussion_btn').click(function(){
			@if(Auth::guest())
				window.location.href = "{{ route('login') }}";
			@else
				$('#new_discussion').slideDown();
				$('#title').focus();
			@endif
		});

		$("#color").spectrum({
		    color: "#333639",
		    preferredFormat: "hex",
		    containerClassName: 'chatter-color-picker',
		    cancelText: '',
    		chooseText: 'close',
		    move: function(color) {
				$("#color").val(color.toHexString());
			}
		});

		@if (count($errors) > 0)
			$('#new_discussion').slideDown();
			$('#title').focus();
		@endif


	});
</script>
@stop
