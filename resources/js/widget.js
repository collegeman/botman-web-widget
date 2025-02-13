const isMobile = window.screen.width < 640

let config = window.botmanWidget

let chatWidth = new String(isMobile ? config.mobileWidth : config.desktopWidth)
if (chatWidth.indexOf('%') === -1) {
    chatWidth += 'px'
}

let chatHeight = new String(isMobile ? config.mobileHeight : config.desktopHeight)
if (chatHeight.indexOf('%') === -1) {
    chatHeight += 'px'
}

let frameEndpoint = config.frameEndpoint
if (isMobile) {
    if (frameEndpoint.indexOf('?') === -1) {
        frameEndpoint += '?mobile=true'
    } else {
        frameEndpoint += '&mobile=true'
    }
}

let beaconEndpoint = config.beaconEndpoint
if (isMobile) {
    if (beaconEndpoint.indexOf('?') === -1) {
        beaconEndpoint += '?mobile=true'
    } else {
        beaconEndpoint += '&mobile=true'
    }
}

// load the chat iframe
let chat = document.createElement('iframe')
chat.src = frameEndpoint
chat.style.position = 'fixed'
chat.style.bottom = isMobile ? '0' : '120px'
chat.style.right = isMobile ? '0' : '40px'
chat.style.zIndex = '1200'
chat.style.width = chatWidth
chat.style.height = chatHeight
chat.style.border = 'none'
chat.style.display = 'none'
document.body.appendChild(chat)

let beacon = document.createElement('iframe')
beacon.src = beaconEndpoint
beacon.style.position = 'fixed'
beacon.style.bottom = isMobile ? '20px' : '40px'
beacon.style.right = isMobile ? '20px' : '40px'
beacon.style.zIndex = '1000'
beacon.style.width = (config.beaconSize + 15) + 'px'
beacon.style.height = (config.beaconSize + 15) + 'px'
beacon.style.border = 'none'
document.body.appendChild(beacon)

let open = false

const callChatMethod = (method, params) => {
    let message = {
        method,
        params
    }
    chat.contentWindow.postMessage(message)
    beacon.contentWindow.postMessage(message)
}

const callBeaconMethod = (method, params) => {
    let message = {
        method,
        params
    }
    beacon.contentWindow.postMessage(message)
}

const relayMessageEvent = (event) => {
    if (event.data.method?.indexOf('botman-web-widget.chat.') !== -1) {
        callBeaconMethod(event.data.method, event.data.params)
    }
    if (event.data.method?.indexOf('botman-web-widget.beacon.') !== -1) {
        callChatMethod(event.data.method, event.data.params)
    }
}

const onToggle = () => {
    relayMessageEvent({
        data: {
            method: 'botman-web-widget.widget.toggle',
            params: { open }
        }
    })
    chat.style.display = open ? 'block' : 'none'
}

const botmanChatWidget = {
    open () {
        open = true
        onToggle()
    },
    close () {
        open = false
        onToggle()
    },
    toggle () {
        open = !open
        onToggle()
    },
    say (text) {

    },
    whisper (text) {

    },
    sayAsBot (text) {
        
    },
    api (text, interactive = false, attachment = null) {
        callChatMethod('botman-web-widget.chat.api', {
            text,
            interactive,
            attachment
        })
    }
}

const initClient = () => {
    window.botmanChatWidget = botmanChatWidget  
}

window.addEventListener('message', (event) => {
    relayMessageEvent(event)
    if (event.data?.method === 'botman-web-widget.chat.init') {
        initClient()
    }
    if (event.data?.method === 'botman-web-widget.beacon.click') {
        botmanChatWidget.toggle()
    }
    if (event.data?.method === 'botman-web-widget.chat.close') {
        botmanChatWidget.close()
    }
    if (event.data?.method === 'botman-web-widget.chat.esc') {
        botmanChatWidget.close()
    }
    if (event.data?.method === 'botman-web-widget.beacon.esc') {
        botmanChatWidget.close()
    }
    if (event.data?.method === 'botman-web-widget.chat.api.response') {
        console.log(event.data.params)
    }
    if (event.data?.method === 'botman-web-widget.chat.api.error') {
        console.log(event.data.params)
    }
})

