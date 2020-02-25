<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios", uniqueConstraints={@ORM\UniqueConstraint(name="IdUsuario", columns={"IdUsuario"})})
 * @ORM\Entity
 */
class Usuarios implements UserInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="Correo", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $correo;

    /**
     * @var string
     *
     * @ORM\Column(name="IdUsuario", type="string", length=50, nullable=false)
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellidos", type="string", length=255, nullable=false)
     */
    private $apellidos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaNacimiento", type="date", nullable=false)
     */
    private $fechanacimiento;

    /**
     * @var int
     *
     * @ORM\Column(name="Telefono", type="integer", nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="Direccion", type="string", length=50, nullable=false)
     */
    private $direccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Avatar", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $avatar = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="Clave", type="string", length=255, nullable=false)
     */
    private $clave;

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function getIdusuario(): ?string
    {
        return $this->idusuario;
    }

    public function setIdusuario(string $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getFechanacimiento(): ?\DateTimeInterface
    {
        return $this->fechanacimiento;
    }

    public function setFechanacimiento(\DateTimeInterface $fechanacimiento): self
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function setClave(string $clave): self
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(array(
            $this->correo,
            $this->clave,
            $this->avatar,
            $this->nombre,
            $this->apellidos,
            $this->telefono,
            $this->fechanacimiento,
            $this->direccion,
        ));
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->correo,
            $this->clave,
            $this->avatar,
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->getClave();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getCorreo();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        return;
    }
}
