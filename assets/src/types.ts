declare global {
  interface Window {
    your_plugin_name: any
  }
}

export type PluginObject = {
  security_token: string
  settings_url: string
  strings: PluginStrings
}

export type PluginStrings = {
  dashboard: {
    title: string
    description: string
    links: {
      github: string
    }
  }
  settings: {
    title: string
    description: string
    form: {
      inputs: {
        enable: {
          label: string
          description: string
        }
      }
      submit: string
      reset: string
    }
  }
}

export type UseFetch = () => {
  get: (url: string) => Promise<any>
  send: (url: string, data: any) => Promise<any>
}

export type ApiResponse = {
  success: boolean
  message: string
  data: any
}
