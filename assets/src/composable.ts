import type { PluginStrings, UseFetch } from './types'
import { Notyf } from 'notyf'

export const pluginStrings: PluginStrings = window.your_plugin_name.strings

export const notify: Notyf = new Notyf({
  duration: 3000,
  position: {
    x: 'center',
    y: 'bottom'
  }
})

export const useFetch: UseFetch = () => {
  const get = async (url: string) => {
    const headers = new Headers()
    headers.append('Accept', 'application/json')
    headers.append('X-WP-Nonce', window.your_plugin_name.security_token)

    const options = {
      method: 'GET',
      headers
    }

    return await fetch(url, options).then((res) => {
      if (res.status >= 400) {
        throw new Error(res.statusText)
      }
      return res.json()
    })
  }

  const send = async (url: string, data: any) => {
    const headers = new Headers()
    headers.append('Content-Type', 'application/json')
    headers.append('Accept', 'application/json')
    headers.append('X-WP-Nonce', window.your_plugin_name.security_token)

    const options = {
      method: 'POST',
      headers,
      body: JSON.stringify(data)
    }

    return await fetch(url, options).then((res) => {
      if (res.status === 403) {
        throw new Error(res.statusText)
      }
      if (res.status >= 400) {
        throw new Error(res.statusText)
      }
      return res.json()
    })
  }
  return {
    get,
    send
  }
}
