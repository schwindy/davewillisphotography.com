﻿// --------------------------------------------------------------------------------------------------------------------
// <copyright file="Deinterlace.cs" company="HandBrake Project (http://handbrake.fr)">
//   This file is part of the HandBrake source code - It may be used under the terms of the GNU General Public License.
// </copyright>
// <summary>
//   Defines the Deinterlace type.
// </summary>
// --------------------------------------------------------------------------------------------------------------------

namespace HandBrake.ApplicationServices.Interop.Model.Encoding
{
    using HandBrake.ApplicationServices.Attributes;

    /// <summary>
    /// The deinterlace.
    /// </summary>
    public enum Deinterlace
    {
        [ShortName("fast")]
        Fast,

        [ShortName("slow")]
        Slow,

        [ShortName("slower")]
        Slower,

        [ShortName("bob")]
        Bob,

        [ShortName("custom")]
        Custom
    }
}
