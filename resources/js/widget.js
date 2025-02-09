let config = window.botmanWidget || {
    chatServer: '/botman',
    frameEndpoint: '/botman/chat',
    timeFormat: 'HH:MM',
    dateTimeFormat: 'm/d/yy HH:MM',
    title: 'BotMan Widget',
    introMessage: null,
    placeholderText: 'Send a message...',
    displayMessageTime: true,
    mainColor: '#408591',
    bubbleBackground: '#408591',
    bubbleAvatarUrl: null,
    desktopHeight: 450,
    desktopWidth: 370,
    mobileHeight: '100%',
    mobileWidth: 300,
    videoHeight: 160,
    aboutLink: 'https://github.com/collegeman/botman-web-widget',
    aboutText: 'Powered by BotMan',
    userId: null,
}

// load the iframe
let iframe = document.createElement('iframe')
iframe.src = config.frameEndpoint
iframe.style.position = 'fixed'
iframe.style.bottom = '10px'
iframe.style.right = '10px'
iframe.style.zIndex = '1000'
iframe.style.width = config.desktopWidth
iframe.style.height = config.desktopHeight
iframe.style.border = 'none'
iframe.onload = () => iframe.contentWindow.postMessage(config, '*')
document.body.appendChild(iframe)

// listen for messages from the iframe
window.addEventListener('message', (event) => {
    console.log(event.data)
})

// create a chat badge
let badge = document.createElement('div')
badge.style.position = 'fixed'
badge.style.bottom = '10px'
badge.style.right = '10px'
badge.style.zIndex = '1000'
badge.style.backgroundColor = 'blue'
badge.style.color = 'white'
badge.style.padding = '10px'
badge.style.borderRadius = '5px'
document.body.appendChild(badge)