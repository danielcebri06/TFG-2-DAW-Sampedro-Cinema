import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-sesion-form',
  standalone: false,
  templateUrl: './admin-sesion-form.html',
  styleUrl: './admin-sesion-form.css'
})
export class AdminSesionForm implements OnInit {

  sesion: any = {
    fecha_hora: '',
    precio: null,
    id_pelicula: null,
    id_sala: null
  };

  peliculas: any[] = [];

  salas: any[] = [
    { id_sala: 1, nombre: 'Sala 1' },
    { id_sala: 2, nombre: 'Sala 2' }
  ];

  modoEditar = false;
  idSesion: number | null = null;

  cargando = false;
  guardando = false;
  error = '';
  mensaje = '';

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarPeliculas();

    const id = this.route.snapshot.paramMap.get('id');

    if (id) {
      this.modoEditar = true;
      this.idSesion = Number(id);
      this.cargarSesion(this.idSesion);
    }
  }

  cargarSesion(id: number): void {
    this.cargando = true;
    this.error = '';

    this.adminService.obtenerSesion(id).subscribe({
      next: (datos) => {
        this.sesion = {
          fecha_hora: this.formatearFechaParaInput(datos.fecha_hora),
          precio: datos.precio,
          id_pelicula: datos.id_pelicula,
          id_sala: datos.id_sala
        };

        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar sesión:', error);
        this.error = 'No se pudo cargar la sesión.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  guardarSesion(): void {
    this.guardando = true;
    this.error = '';
    this.mensaje = '';

    const sesionEnviar = {
      ...this.sesion,
      fecha_hora: this.formatearFechaParaBackend(this.sesion.fecha_hora)
    };

    if (this.modoEditar && this.idSesion !== null) {
      this.adminService.modificarSesion(this.idSesion, sesionEnviar).subscribe({
        next: () => {
          this.mensaje = 'Sesión modificada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/sesiones']);
        },
        error: (error) => {
          console.error('Error al modificar sesión:', error);
          this.error = 'No se pudo modificar la sesión.';
          this.guardando = false;
          this.cd.detectChanges();
        }
      });
    } else {
      this.adminService.crearSesion(sesionEnviar).subscribe({
        next: () => {
          this.mensaje = 'Sesión creada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/sesiones']);
        },
        error: (error) => {
          console.error('Error al crear sesión:', error);
          this.error = 'No se pudo crear la sesión.';
          this.guardando = false;
          this.cd.detectChanges();
        }
      });
    }
  }

  cargarPeliculas(): void {
    this.adminService.listarPeliculas().subscribe({
      next: (datos) => {
        this.peliculas = datos;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar películas para el desplegable:', error);
        this.error = 'No se pudieron cargar las películas.';
        this.cd.detectChanges();
      }
    });
  }

  private formatearFechaParaInput(fecha: string): string {
    if (!fecha) {
      return '';
    }

    return fecha.replace(' ', 'T').substring(0, 16);
  }

  private formatearFechaParaBackend(fecha: string): string {
    if (!fecha) {
      return '';
    }

    return fecha.replace('T', ' ') + ':00';
  }
}
