import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { GestionService } from '../services/gestion.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css',
  providers: [GestionService]
})
export class DashboardComponent implements OnInit {
  gestiones: any[] = [];
  error = '';

  constructor(private gestionService: GestionService) { }

  ngOnInit(): void {
    this.gestionService.obtenerGestiones().subscribe({
      next: (res: any) => {
        this.gestiones = res.gestiones;
      },
      error: (err: any) => {
        console.error(err);
        this.error = 'No se pudieron cargar tus gestiones.';
      }
    });
  }
}
