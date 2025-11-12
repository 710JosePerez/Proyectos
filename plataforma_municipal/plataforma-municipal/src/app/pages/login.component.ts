import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css',
})
export class LoginComponent {
  modoRegistro = false;

  // Datos para ambos formularios
  dpi = '';
  password = '';

  // Datos extra para registro
  nombre = '';
  apellido = '';
  email = '';
  telefono = '';
  direccion = '';

  error = '';
  mensaje = '';

  constructor(private authService: AuthService, private router: Router) { }

  // Cambiar entre login y registro
  cambiarModo() {
    this.modoRegistro = !this.modoRegistro;
    this.error = '';
    this.mensaje = '';
  }

  // Iniciar sesión
  iniciarSesion() {
    this.authService.login({ dpi: this.dpi, password: this.password }).subscribe({
      next: (res) => {
        this.authService.guardarToken(res.token);
        this.router.navigate(['/dashboard']);
      },
      error: (err) => {
        console.error(err);
        this.error = err.error?.error || 'Error al iniciar sesión.';
      }
    });
  }

  // Registrar usuario nuevo
  registrarUsuario() {
    if (!this.dpi || !this.password || !this.nombre || !this.apellido || !this.email || !this.telefono || !this.direccion) {
      this.error = 'Todos los campos son obligatorios.';
      return;
      
    }

    const nuevoUsuario = {
      dpi: this.dpi,
      password: this.password,
      nombre: this.nombre,
      apellido: this.apellido,
      email: this.email,
      telefono: this.telefono,
      direccion: this.direccion
    };

    this.authService.registrar(nuevoUsuario).subscribe({
      next: (res) => {
        this.mensaje = res.message;
        this.error = '';
        this.modoRegistro = false;
        this.limpiarCampos();
      },
      error: (err) => {
        console.error(err);
        this.error = err.error?.error || 'Error al registrar usuario.';
      }
    });
  }

  limpiarCampos() {
    this.dpi = '';
    this.password = '';
    this.nombre = '';
    this.apellido = '';
    this.email = '';
    this.telefono = '';
    this.direccion = '';
  }
  irARegistro() {
    this.router.navigate(['/registro']);
  }
}


