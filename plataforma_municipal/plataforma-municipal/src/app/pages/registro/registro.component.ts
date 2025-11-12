import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-registro',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './registro.component.html',
  styleUrls: ['./registro.component.css'],

})
export class RegistroComponent {
  nombre = '';
  apellido = '';
  email = '';
  password = '';
  telefono = '';
  direccion = '';
  dpi = '';
  error = '';
  exito = '';

  constructor(private authService: AuthService, private router: Router) { }

  registrarse() {
    // Validamos que todos los campos estén llenos
    if (!this.nombre || !this.apellido || !this.email || !this.password || !this.telefono || !this.direccion || !this.dpi) {
      this.error = 'Todos los campos son obligatorios';
      return;
    }

    const datos = {
      nombre: this.nombre,
      apellido: this.apellido,
      email: this.email,
      password: this.password,
      telefono: this.telefono,
      direccion: this.direccion,
      dpi: this.dpi
    };

    this.authService.registrar(datos).subscribe({
      next: (res) => {
        this.exito = res.message;
        this.error = '';
        // Redirigir al login después de un tiempo
        setTimeout(() => {
          this.router.navigate(['/login']);
        }, 2000);
      },
      error: (err) => {
        this.error = err.error?.error || 'Ocurrió un error al registrar.';
        this.exito = '';
      }
    });
  }
}
