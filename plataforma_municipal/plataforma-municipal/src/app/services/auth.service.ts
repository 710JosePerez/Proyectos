import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {
    private apiUrl = environment.apiUrl;

    constructor(private http: HttpClient) { }

    login(datos: { dpi: string; password: string }): Observable<any> {
        return this.http.post(`${this.apiUrl}/login`, datos);
    }

    registrar(datos: any): Observable<any> {
        return this.http.post(`${this.apiUrl}/registro`, datos);
    }

    obtenerToken(): string | null {
        if (typeof window !== 'undefined') {
            return localStorage.getItem('token');
        }
        return null;
    }

    guardarToken(token: string): void {
        if (typeof window !== 'undefined') {
            localStorage.setItem('token', token);
        }
    }

    estaAutenticado(): boolean {
        return !!this.obtenerToken();
    }

    cerrarSesion(): void {
        localStorage.removeItem('token');
    }
}
