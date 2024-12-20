function removeTrailingSlash(url: string) {
    return url.replace(/\/$/, '');
}

async function request<T>(method: string, endpoint: string, data?: any): Promise<{
    success: boolean,
    status: number,
    data?: T,
    response?: Response,
    error?: Error
}> {
    try {
        const response = await fetch(`${removeTrailingSlash(import.meta.env.VITE_API)}/${removeTrailingSlash(endpoint)}`, {
            method,
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include',
            body: data ? JSON.stringify(data) : undefined
        });
        return {
            success: response.ok,
            status: response.status,
            data: response.status === 204 ? undefined : await response.json(),
            response
        };
    } catch (error) {
        console.error(error);
        return {
            success: false,
            status: 0,
            error: error as Error
        };
    }
}

export async function get<T>(endpoint: string) {
    return request<T>('GET', endpoint);
}

export async function post<T>(endpoint: string, data?: any) {
    return request<T>('POST', endpoint, data);
}

export async function patch<T>(endpoint: string, data: any): Promise<{
    success: boolean,
    status: number,
    data?: T,
    error?: Error
}> {
    return request<T>('PATCH', endpoint, data);
}

export async function del(endpoint: string): Promise<{
    success: boolean,
    status: number,
    error?: Error
}> {
    return request<null>('DELETE', endpoint);
}

