<!--<chatbox :user="{{ App\Models\User::with(['chatStatus', 'chatroom', 'group'])->find(auth()->id()) }}"></chatbox>-->

<div id="vue">
<!-- <script src="/js/chat.js?id=13167f8bcd432f6181864fc0432eb3ec" crossorigin="anonymous" nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}"></script> -->
<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="panel panel-chat">
        <div class="panel-heading">
            <div data-v-28766852="" id="frameHeader" class="panel-heading">
                <div data-v-28766852="" class="button-holder no-space">
                    <div data-v-28766852="" class="button-left">
                        <h4 data-v-28766852=""><i data-v-28766852="" class="fas fa-comment-dots"></i> Chatbox Discord
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body" style="padding: 0px;">
            <widgetbot server="838217297478680596" channel="838217297478680599" width="100%" height="600"></widgetbot>
        </div>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/@widgetbot/html-embed" nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}"></script>
