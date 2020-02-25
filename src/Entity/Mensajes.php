<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensajes
 *
 * @ORM\Table(name="mensajes", indexes={@ORM\Index(name="rec_mj_fk", columns={"Receptor"}), @ORM\Index(name="emi_mj_fk", columns={"Emisor"})})
 * @ORM\Entity
 */
class Mensajes
{
    /**
     * @var int
     *
     * @ORM\Column(name="Codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codigo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Asunto", type="string", length=255, nullable=true, options={"default"="'(Sin asunto)'"})
     */
    private $asunto = '\'(Sin asunto)\'';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Mensaje", type="string", length=4000, nullable=true, options={"default"="NULL"})
     */
    private $mensaje = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Fichero", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $fichero = 'NULL';

    /**
     * @var int
     *
     * @ORM\Column(name="Leido", type="integer", nullable=false)
     */
    private $leido;

    /**
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Emisor", referencedColumnName="Correo")
     * })
     */
    private $emisor;

    /**
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Receptor", referencedColumnName="Correo")
     * })
     */
    private $receptor;

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function getAsunto(): ?string
    {
        return $this->asunto;
    }

    public function setAsunto(?string $asunto): self
    {
        $this->asunto = $asunto;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getFichero(): ?string
    {
        return $this->fichero;
    }

    public function setFichero(?string $fichero): self
    {
        $this->fichero = $fichero;

        return $this;
    }

    public function getLeido(): ?int
    {
        return $this->leido;
    }

    public function setLeido(int $leido): self
    {
        $this->leido = $leido;

        return $this;
    }

    public function getEmisor(): ?Usuarios
    {
        return $this->emisor;
    }

    public function setEmisor(?Usuarios $emisor): self
    {
        $this->emisor = $emisor;

        return $this;
    }

    public function getReceptor(): ?Usuarios
    {
        return $this->receptor;
    }

    public function setReceptor(?Usuarios $receptor): self
    {
        $this->receptor = $receptor;

        return $this;
    }


}
