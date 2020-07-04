<section id="chat">
	<div class="container" ng-controller="ChatAppCtrl">
	  <div class="row">
	    <div class="col-lg-10 mx-auto">
				<h2><p>
					Chat<br>
				</p>
					<small>Unterhalte dich hier mit anderen Fußballfreunden</small>
				</h2>
				<hr>
				        <div class="box box-warning direct-chat direct-chat-warning">
				            <div class="box-body">
				                <div class="direct-chat-messages">
													<div class="direct-chat-msg" ng-repeat="message in messages" ng-if="historyFromId < message.id" ng-class="{'right':!message.me}">
															<div class="direct-chat-info clearfix">
																	<span class="direct-chat-name" ng-class="{'float-left':message.me, 'float-right':!message.me}">{{ message.username }}</span>
																	<span class="direct-chat-timestamp text-right" ng-class="{'float-left':!message.me, 'float-right':message.me}">{{ message.date }}</span>
															</div>
															<img class="direct-chat-img" ng-src="images/profilbild/{{message.profilbild}}" alt="">
															<div class="direct-chat-text right">
																	<span>{{ message.message }}</span>
															</div>
													</div>
													</div>
				                </div>
				                <div class="box-footer">
				                    <form ng-submit="saveMessage()">
				                        <div class="input-group">
				                            <input type="text" placeholder="Schreibe hier deine Nachricht rein" autofocus="autofocus" class="form-control" ng-model="me.message" ng-enter="saveMessage()">
				                            <span class="input-group-btn">
				                            <button type="submit" class="btn btn-warning btn-flat">Send</button>
				                            </span>
				                        </div>
				                    </form>
				                    <div class="clearfix">
				                            <span class="badge badge-pill badge-secondary float-left">Online users: {{ online.total || '1' }}</span>
				                            <!--<a class="btn btn-sm btn-warning float-right ml10" href="" data-toggle="modal" data-target="#choose-name">Change username</a>-->
				                            <!--<a class="btn btn-xs btn-warning float-right" href="" data-toggle="modal" data-target="#clear-history">Clear history</a>-->
				                            <!--<span class="float-right">Use shortcodes:
				                                <span class="badge badge-pill badge-secondary">[img]http://image.url[/img]</span>
				                                <span class="badge badge-pill badge-secondary">[url]http://url.link/[/url]</span>
				                            </span>-->
				                        </div>
				                </div>
				            </div>
				        </div>
				    </div>

				    <div class="modal" id="choose-name">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <form>
				                    <div class="modal-header">
				                        <button type="button" class="close" data-dismiss="modal">
				                            <span aria-hidden="true">&times;</span>
				                            <span class="sr-only">Close</span>
				                        </button>
				                        <h4 class="modal-title">Choose nickname</h4>
				                    </div>
				                    <div class="modal-body">
				                        <label class="radio">Enter your username</label>
				                        <input class="form-control" ng-model="me.username" autofocus="autofocus">
				                    </div>
				                    <div class="modal-footer">
				                        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
				                    </div>
				                </form>
				            </div>
				        </div>
				    </div>
				    <div class="modal" id="clear-history">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <form>
				                    <div class="modal-header">
				                        <button type="button" class="close" data-dismiss="modal">
				                            <span aria-hidden="true">&times;</span>
				                            <span class="sr-only">Close</span>
				                        </button>
				                        <h4 class="modal-title">Chat history</h4>
				                    </div>
				                    <div class="modal-body">
				                        <label class="radio">Are you sure to clear chat history?</label>
				                    </div>
				                    <div class="modal-footer">
				                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
				                        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" ng-click="clearHistory()">Accept</button>
				                    </div>
				                </form>
				            </div>
				        </div>
							</div>



				<!--<iframe style="border:none; overflow:hidden;" width="100%" height="550px" src="includes/spachat/spachat.php"></iframe>-->
    </div>
  </div>
</section>
