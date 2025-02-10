let config = window.botmanWidget

// load the chat iframe
let chat = document.createElement('iframe')
chat.src = config.frameEndpoint
chat.style.position = 'fixed'
chat.style.bottom = '120px'
chat.style.right = '40px'
chat.style.zIndex = '1200'
chat.style.width = config.desktopWidth + 'px'
chat.style.height = config.desktopHeight + 'px'
chat.style.border = 'none'
chat.style.display = 'none'
document.body.appendChild(chat)

let beacon = document.createElement('iframe')
beacon.src = config.beaconEndpoint
beacon.style.position = 'fixed'
beacon.style.bottom = '40px'
beacon.style.right = '40px'
beacon.style.zIndex = '1000'
beacon.style.width = (config.beaconSize + 15) + 'px'
beacon.style.height = (config.beaconSize + 15) + 'px'
beacon.style.border = 'none'
document.body.appendChild(beacon)

let open = false

const botmanChatWidget = {
    open () {
        open = true
        this.sendToggleMessage()
    },
    close () {
        open = false
        this.sendToggleMessage()
    },
    toggle () {
        open = !open
        this.sendToggleMessage()
    },
    sendToggleMessage () {
        let message = {
            type: 'botman-web-widget.widget.toggle',
            data: {
                open,
            }
        }
        chat.contentWindow.postMessage(message)
        beacon.contentWindow.postMessage(message)
        chat.style.display = open ? 'block' : 'none'
    },
    say (text) {

    },
    whisper (text) {

    },
    sayAsBot (text) {
        
    }
}

const initClient = () => {
    window.botmanChatWidget = botmanChatWidget  
}

window.addEventListener('message', (event) => {
    if (event.data?.type === 'botman-web-widget.chat.init') {
        initClient()
    }
    if (event.data?.type === 'botman-web-widget.beacon.click') {
        botmanChatWidget.toggle()
    }
})

