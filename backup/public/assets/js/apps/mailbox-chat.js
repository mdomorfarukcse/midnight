$('.search > input').on('keyup', function() {
  var rex = new RegExp($(this).val(), 'i');
    $('.people .person').hide();
    $('.people .person').filter(function() {
        return rex.test($(this).text());
    }).show();
});

$('.user-list-box .person').on('click', function(event) {
    if ($(this).hasClass('.active')) {
        return false;
    } else {

        var result = $.ajax({type: "GET", url: '/ajax/' + $('.chat-input input#message').data('who') + '/support/detail/' + $(this).data('id'), async: false}).responseText;

        $('#chat-conversation-box-scroll').html(result);

        $('.chat-input input#message').data('id', $(this).data('id'));

        var findChat = $(this).attr('data-chat');
        var personName = $(this).find('.user-name').text();
        var vehicleName = $(this).find('.vehiclename').text();
        var personImage = $(this).find('img').attr('src');
        var hideTheNonSelectedContent = $(this).parents('.chat-system').find('.chat-box .chat-not-selected').hide();
        var showChatInnerContent = $(this).parents('.chat-system').find('.chat-box .chat-box-inner').show();

        if (window.innerWidth <= 767) {
          $('.chat-box .current-chat-user-name .name').html(personName.split(' ')[0]);
          $('.chat-box .current-chat-user-name .vehicle').html(' - '+vehicleName);
        } else if (window.innerWidth > 767) {
          $('.chat-box .current-chat-user-name .name').html(personName);
          $('.chat-box .current-chat-user-name .vehicle').html(' - '+vehicleName);
		  
        }
        $('.chat-box .current-chat-user-name img').attr('src', personImage);
        $('.chat').removeClass('active-chat');
        $('.user-list-box .person').removeClass('active');
        $('.chat-box .chat-box-inner').css('height', '100%');
        $('.chat-box .overlay-phone-call').css('display', 'block');
        $('.chat-box .overlay-video-call').css('display', 'block');
        $(this).addClass('active');
        $('.chat[data-chat = '+findChat+']').addClass('active-chat');
    }
    if ($(this).parents('.user-list-box').hasClass('user-list-box-show')) {
      $(this).parents('.user-list-box').removeClass('user-list-box-show');
    }
    $('.chat-meta-user').addClass('chat-active');
    $('.chat-box').css('height', 'calc(100vh - 232px)');
    $('.chat-footer').addClass('chat-active');

  const ps = new PerfectScrollbar('.chat-conversation-box', {
    suppressScrollX : true
  });

  const getScrollContainer = document.querySelector('.chat-conversation-box');
  getScrollContainer.scrollTop = 0;
});

const ps = new PerfectScrollbar('.people', {
  suppressScrollX : true
});

$('.mail-write-box').on('keydown', function(event) {
    if(event.key === 'Enter') {

        var chatInput = $(this);
        var my_file = document.getElementById('chat_file');
        var chatMessageValue = chatInput.val();
        if ((!my_file.files && !my_file.files[0]) && chatMessageValue === '') { return; }

        if(chatMessageValue !== '') {
            $messageHtml = '<div class="bubble me">' + chatMessageValue + '</div>';
            var appendMessage = $(this).parents('.chat-system').find('.active-chat').append($messageHtml);
            const getScrollContainer = document.querySelector('.chat-conversation-box');
            getScrollContainer.scrollTop = getScrollContainer.scrollHeight;
            var clearChatInput = chatInput.val('');
        }

        var form_data = new FormData();

        form_data.append('supportId', $('.chat-input input#message').data('id'));
        form_data.append('message', chatMessageValue);



        if(my_file.files && my_file.files[0]){
            form_data.append('file', my_file.files[0]);

            $file_html = '<div class="bubble me">File Uploaded</div>';
            var appendMessage = $(this).parents('.chat-system').find('.active-chat').append($file_html);

            const getScrollContainer = document.querySelector('.chat-conversation-box');
            getScrollContainer.scrollTop = getScrollContainer.scrollHeight;
            my_file.value = null;
        }

        $.ajax({
            url: "/ajax/" + $('.chat-input input#message').data('who') + "/support/send-message", // <-- point to server-side PHP script
            dataType: 'text',  // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response){},
            error: function (error) {}
        });

    }
})

$('.hamburger, .chat-system .chat-box .chat-not-selected p').on('click', function(event) {
  $(this).parents('.chat-system').find('.user-list-box').toggleClass('user-list-box-show')
})
