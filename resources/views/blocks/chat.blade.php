<!--<chatbox :user="{{ App\Models\User::with(['chatStatus', 'chatroom', 'group'])->find(auth()->id()) }}"></chatbox>-->

<div id="vue">
<!-- <script src="/js/chat.js?id=13167f8bcd432f6181864fc0432eb3ec" crossorigin="anonymous" nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}"></script> -->

<script src="//cdn.jsdelivr.net/npm/@widgetbot/crate@3" nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
                new Crate({
                        server: '838217297478680596',
                        channel: '838217297478680599'
                        })
</script>
